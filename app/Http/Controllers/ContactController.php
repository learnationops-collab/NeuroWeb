<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display contacts based on user's role.
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->roles->first()->name ?? 'estudiante';
        
        // Obtener contactos según el rol
        $contactSections = $user->getContactsByRole();
        
        return view('contacts.index', compact('contactSections', 'userRole'));
    }

    /**
     * Show contact details.
     */
    public function show($id)
    {
        $user = Auth::user();
        $userRole = $user->roles->first()->name ?? 'estudiante';
        
        // Obtener todos los contactos permitidos para verificar acceso
        $allowedContacts = collect();
        $contactSections = $user->getContactsByRole();
        
        foreach ($contactSections as $contacts) {
            $allowedContacts = $allowedContacts->merge($contacts);
        }
        
        $contact = $allowedContacts->where('id', $id)->first();
        
        if (!$contact) {
            abort(404, 'Contacto no encontrado o no tienes permisos para verlo.');
        }
        
        return view('contacts.show', compact('contact', 'userRole'));
    }
}
