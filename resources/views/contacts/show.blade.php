@extends('layouts.app')

@section('title', 'Perfil de ' . $contact->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Botón volver -->
        <div class="mb-6">
            <a href="{{ route('contacts.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver a Contactos
            </a>
        </div>

        <!-- Perfil del contacto -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header con avatar y info básica -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8">
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-3xl">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <h1 class="text-3xl font-bold">{{ $contact->name }}</h1>
                        <p class="text-xl opacity-90">{{ $contact->email }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($contact->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white">
                                    @if($role->name === 'admin')
                                        👑 Administrador
                                    @elseif($role->name === 'neuro_team')
                                        🧠 Equipo NeuroWeb
                                    @else
                                        🎓 Estudiante
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del contacto -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de Contacto</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                <span class="text-gray-600">{{ $contact->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a1 1 0 01-1 1h-1v9a2 2 0 01-2 2H7a2 2 0 01-2-2V8H4a1 1 0 01-1-1V6a2 2 0 012-2h3z"></path>
                                </svg>
                                <span class="text-gray-600">ID: {{ $contact->id }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a1 1 0 01-1 1h-1v9a2 2 0 01-2 2H7a2 2 0 01-2-2V8H4a1 1 0 01-1-1V6a2 2 0 012-2h3z"></path>
                                </svg>
                                <span class="text-gray-600">Miembro desde: {{ $contact->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones disponibles -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Disponibles</h3>
                        <div class="space-y-3">
                            <a href="{{ route('chat') }}?contact={{ $contact->id }}" 
                               class="flex items-center w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Iniciar Conversación
                            </a>
                            
                            @if($userRole === 'admin' || $userRole === 'neuro_team')
                                <button class="flex items-center w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Ver Historial
                                </button>
                            @endif

                            <button class="flex items-center w-full px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Marcar como Favorito
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Información adicional según el rol -->
                @if($contact->roles->first())
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">Información de Rol</h4>
                        @php $primaryRole = $contact->roles->first()->name; @endphp
                        @if($primaryRole === 'admin')
                            <p class="text-gray-600">
                                <span class="font-medium">Administrador:</span> Supervisa el sistema y coordina las actividades del equipo NeuroWeb.
                            </p>
                        @elseif($primaryRole === 'neuro_team')
                            <p class="text-gray-600">
                                <span class="font-medium">Equipo NeuroWeb:</span> Especialista que brinda apoyo y orientación a los estudiantes.
                            </p>
                        @else
                            <p class="text-gray-600">
                                <span class="font-medium">Estudiante:</span> Miembro activo de la plataforma NeuroWeb en proceso de aprendizaje.
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Estado de actividad (placeholder) -->
                <div class="mt-6 flex items-center justify-between py-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Estado: Activo</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Última actividad: {{ $contact->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
