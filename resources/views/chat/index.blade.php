@extends('layouts.app')

@section('content')
<div class="chat-container">
    {{-- Lista de conversaciones según el rol del usuario --}}
    <div class="contacts-list {{ $userRole === 'admin' ? 'admin-options' : '' }}">
        @if ($userRole === 'admin')
            <h2>Opciones de Admin</h2>
            <ul class="admin-actions">
                <li><a href="{{ route('admin.users.index') }}">👥 Gestionar Usuarios</a></li>
                <li><a href="{{ route('contacts.index') }}">📞 Ver Contactos</a></li>
                <li>🔍 Buscar Conversación</li>
                <li>📊 Estadísticas de Chat</li>
            </ul>
            <hr>
            <h2>Conversaciones (Todas)</h2>
        @elseif ($userRole === 'neuro_team')
            <h2>Chats del Equipo y Estudiantes</h2>
        @else
            <h2>Mis Contactos</h2>
        @endif

        <ul class="conversation-items">
            @forelse($conversations as $conversation)
                @php
                    $otherUser = $conversation->getOtherParticipant(Auth::id());
                    $unreadCount = $conversation->getUnreadCount(Auth::id());
                    $lastMessage = $conversation->latestMessage;
                @endphp
                
                <li class="conversation-item {{ $activeConversation && $activeConversation->id == $conversation->id ? 'active' : '' }}" 
                    data-conversation-id="{{ $conversation->id }}"
                    onclick="loadConversation({{ $conversation->id }})">
                    
                    <span class="contact-name">
                        {{ $otherUser->name ?? 'Chat Grupal' }}
                        @if($unreadCount > 0)
                            <span class="unread-count">({{ $unreadCount }})</span>
                        @endif
                    </span>
                    
                    @if($lastMessage)
                        <small class="last-message-preview">
                            {{ $lastMessage->user->id == Auth::id() ? 'Tú: ' : '' }}{{ Str::limit($lastMessage->content, 25) }}
                        </small>
                    @endif
                </li>
            @empty
                <li class="no-conversations">
                    <em>No tienes conversaciones aún</em><br>
                    <small><a href="{{ route('contacts.index') }}">Ver contactos</a> para iniciar una conversación</small>
                </li>
            @endforelse
        </ul>
    </div>

    {{-- Área del chat principal --}}
    <div class="chat-area">
        @if($activeConversation)
            @php
                $otherUser = $activeConversation->getOtherParticipant(Auth::id());
            @endphp
            
            <div class="message-list">
                <h3>Chat con {{ $otherUser->name ?? 'Chat Grupal' }} 
                    <button class="refresh-btn" onclick="refreshMessages()" title="Actualizar">🔄</button>
                </h3>

                <div id="messagesContainer">
                    @foreach($messages as $message)
                        <div class="message-item {{ $message->user_id == Auth::id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                            @if($message->user_id != Auth::id())
                                <strong>{{ $message->user->name }}:</strong>
                            @endif
                            {{ $message->content }}
                            <small class="message-time">{{ $message->created_at->format('H:i') }}</small>
                        </div>
                    @endforeach
                </div>

            </div>
            
            <form id="messageForm" class="message-input">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                <input type="text" 
                       id="messageInput"
                       name="content" 
                       placeholder="Escribe un mensaje..."
                       maxlength="1000"
                       required
                       autocomplete="off"
                       style="flex-grow: 1;">
                <button type="submit">Enviar</button>
            </form>
        @else
            <div class="message-list">
                <h3>Selecciona una conversación</h3>
                <div class="no-chat-selected">
                    <p>Elige una conversación de la lista o <a href="{{ route('contacts.index') }}">ve a tus contactos</a> para iniciar una nueva.</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Meta tags para JavaScript --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user-id" content="{{ Auth::id() }}">
@if($activeConversation)
    <meta name="conversation-id" content="{{ $activeConversation->id }}">
    <meta name="last-message-id" content="{{ $messages->last()?->id ?? 0 }}">
@endif

<style>
{{-- Estilos adicionales para el chat funcional --}}
{{-- Estilos adicionales para funcionalidad del chat --}}
.conversation-item {
    cursor: pointer;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.conversation-item:hover {
    background-color: #f5f5f5;
}

.conversation-item.active {
    background-color: #e3f2fd;
    font-weight: bold;
}

.contact-name {
    font-weight: 500;
}

.unread-count {
    color: #e74c3c;
    font-weight: bold;
}

.last-message-preview {
    color: #666;
    display: block;
    margin-top: 4px;
}

.message-time {
    font-size: 10px;
    color: #999;
    margin-left: 10px;
}

.refresh-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    margin-left: 10px;
}

.no-chat-selected {
    text-align: center;
    color: #666;
    padding: 40px 20px;
}

.admin-actions {
    margin-bottom: 15px;
}

.admin-actions li {
    margin: 8px 0;
}

.admin-actions a {
    text-decoration: none;
    color: #3498db;
}

.admin-actions a:hover {
    text-decoration: underline;
}
</style>

<script src="{{ asset('js/chat.js') }}"></script>
@endsection
