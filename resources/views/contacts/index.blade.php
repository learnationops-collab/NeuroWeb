@extends('layouts.app')

@section('title', 'Contactos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                @if($userRole === 'estudiante')
                    Mis Contactos
                @elseif($userRole === 'neuro_team')
                    Contactos del Equipo
                @else
                    Administración de Contactos
                @endif
            </h1>
            <div class="text-sm text-gray-600">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($userRole === 'admin') bg-red-100 text-red-800
                    @elseif($userRole === 'neuro_team') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $userRole)) }}
                </span>
            </div>
        </div>

        @if(empty($contactSections))
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">👥</div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No hay contactos disponibles</h3>
                <p class="text-gray-600">No tienes contactos asignados en este momento.</p>
            </div>
        @else
            @foreach($contactSections as $sectionName => $contacts)
                <div class="mb-8">
                    <div class="border-b border-gray-200 pb-2 mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            @if($sectionName === 'Administradores')
                                <span class="text-red-500 mr-2">👑</span>
                            @elseif($sectionName === 'Equipo NeuroWeb')
                                <span class="text-blue-500 mr-2">🧠</span>
                            @else
                                <span class="text-green-500 mr-2">🎓</span>
                            @endif
                            {{ $sectionName }} ({{ $contacts->count() }})
                        </h2>
                    </div>

                    @if($contacts->count() === 0)
                        <div class="text-center py-6 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No hay contactos en esta sección</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($contacts as $contact)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 truncate">
                                                {{ $contact->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ $contact->email }}
                                            </p>
                                            <div class="flex flex-wrap gap-1 mb-3">
                                                @foreach($contact->roles as $role)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($role->name === 'admin') bg-red-100 text-red-700
                                                        @elseif($role->name === 'neuro_team') bg-blue-100 text-blue-700
                                                        @else bg-gray-100 text-gray-700
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('contacts.show', $contact->id) }}" 
                                                   class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md transition-colors">
                                                    Ver Perfil
                                                </a>
                                                <a href="{{ route('chat') }}?contact={{ $contact->id }}" 
                                                   class="text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md transition-colors">
                                                    💬 Chatear
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        <!-- Información adicional según el rol -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Información sobre tus contactos
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        @if($userRole === 'estudiante')
                            <p>Como estudiante, puedes contactar con el equipo NeuroWeb para obtener ayuda y orientación.</p>
                        @elseif($userRole === 'neuro_team')
                            <p>Como miembro del equipo, tienes acceso a los administradores y estudiantes para coordinar y brindar apoyo.</p>
                        @else
                            <p>Como administrador, puedes supervisar y coordinar con el equipo NeuroWeb.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
