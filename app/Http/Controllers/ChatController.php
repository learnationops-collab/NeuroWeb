<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Verifica si el usuario existe y si tiene roles
        if ($user && $user->roles->first()) {
            $userRole = $user->roles->first()->name;
        } else {
            // Valor por defecto en caso de que no se encuentre un rol
            $userRole = 'estudiante';
        }
        
        // Retorna la vista y pasa la variable del rol
        return view('chat', ['userRole' => $userRole]);
    }
}
