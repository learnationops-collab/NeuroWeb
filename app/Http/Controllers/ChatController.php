<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->roles->first()->name ?? 'estudiante';
        
        // Obtener todas las conversaciones del usuario
        $conversations = $user->conversations()->with(['users', 'latestMessage.user'])->get();
        
        // Si se especifica un contacto, crear o obtener conversación
        $activeConversation = null;
        $messages = collect();
        
        if ($request->has('contact')) {
            $contactId = $request->get('contact');
            $activeConversation = $user->getConversationWith($contactId);
            $messages = $activeConversation->messages()->with('user')->get();
            
            // Marcar conversación como leída
            $activeConversation->markAsRead($user->id);
        } elseif ($request->has('conversation')) {
            $conversationId = $request->get('conversation');
            $activeConversation = $user->conversations()->with('users')->find($conversationId);
            
            if ($activeConversation) {
                $messages = $activeConversation->messages()->with('user')->get();
                $activeConversation->markAsRead($user->id);
            }
        }
        
        return view('chat.index', compact('userRole', 'conversations', 'activeConversation', 'messages'));
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:1000',
        ]);
        
        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);
        
        // Verificar que el usuario pertenece a la conversación
        if (!$conversation->users->contains($user->id)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        // Crear el mensaje
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'content' => $request->content,
            'type' => 'text',
        ]);
        
        // Actualizar última actividad de la conversación
        $conversation->update(['last_activity' => now()]);
        
        // Crear notificaciones para otros participantes
        foreach ($conversation->users as $participant) {
            if ($participant->id !== $user->id) {
                Notification::createMessageNotification(
                    $participant->id,
                    $user->id,
                    $conversation->id,
                    $request->content
                );
            }
        }
        
        // Cargar usuario para respuesta
        $message->load('user');
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'html' => view('chat.message', compact('message'))->render()
        ]);
    }
    
    /**
     * Get new messages for a conversation
     */
    public function getMessages(Request $request)
    {
        $conversationId = $request->get('conversation_id');
        $lastMessageId = $request->get('last_message_id', 0);
        
        $user = Auth::user();
        $conversation = $user->conversations()->find($conversationId);
        
        if (!$conversation) {
            return response()->json(['error' => 'Conversación no encontrada'], 404);
        }
        
        $messages = $conversation->messages()
            ->with('user')
            ->where('id', '>', $lastMessageId)
            ->get();
        
        $html = '';
        foreach ($messages as $message) {
            $html .= view('chat.message', compact('message'))->render();
        }
        
        return response()->json([
            'messages' => $messages,
            'html' => $html,
            'last_message_id' => $messages->last()?->id ?? $lastMessageId
        ]);
    }
    
    /**
     * Start conversation with a specific user
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        $user = Auth::user();
        $otherUserId = $request->user_id;
        
        // Verificar que el usuario puede contactar al otro usuario
        $allowedContacts = collect();
        $contactSections = $user->getContactsByRole();
        
        foreach ($contactSections as $contacts) {
            $allowedContacts = $allowedContacts->merge($contacts);
        }
        
        if (!$allowedContacts->contains('id', $otherUserId)) {
            return response()->json(['error' => 'No autorizado para contactar este usuario'], 403);
        }
        
        $conversation = $user->getConversationWith($otherUserId);
        
        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'redirect_url' => route('chat') . '?conversation=' . $conversation->id
        ]);
    }
}
