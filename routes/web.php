<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ContactController;

// Ruta para mostrar la vista de autenticación (login/registro)
Route::get('/', function () {
    return view('auth');
})->name('auth');

// Ruta para procesar el registro
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// Ruta para procesar el inicio de sesión
Route::post('/login', [LoginController::class, 'store'])->name('login');

// Ruta para cerrar sesión
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Rutas protegidas que solo pueden ver usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    
    // API endpoints para chat
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.start');
});

// Rutas protegidas solo para administradores
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserManagementController::class)->except(['show']);
});
