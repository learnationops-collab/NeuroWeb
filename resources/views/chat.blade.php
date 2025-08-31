@extends('layouts.app')

@section('content')
    <div class="chat-container">
        {{-- Contenido de la interfaz de chat según el rol del usuario --}}
        @if ($userRole === 'admin')
            {{-- Interfaz para el rol de Administrador --}}
            <div class="contacts-list admin-options">
                <h2>Opciones de Admin</h2>
                <ul>
                    {{-- Acciones y filtros para administradores --}}
                    <li>Gestionar Usuarios de Chat</li>
                    <li>Ver todas las Conversaciones</li>
                    <li>Buscar Conversación por Usuario</li>
                    <li>Reportar Mensajes</li>
                </ul>
                <hr>
                <h2>Conversaciones (Todas)</h2>
                <ul>
                    <li>Conversación 1</li>
                    <li>Conversación 2</li>
                </ul>
            </div>
            
            <div class="chat-area">
                <div class="message-list">
                    <h3>Chat de Admin</h3>
                    <div class="message-item received">Mensaje de un usuario normal.</div>
                    <div class="message-item sent">Respuesta del administrador.</div>
                </div>
                <form class="message-input">
                    <input type="text" placeholder="Escribe un mensaje..." style="flex-grow: 1;">
                    <button type="submit">Enviar</button>
                </form>
            </div>

        @elseif ($userRole === 'neuro_team')
            {{-- Interfaz para el rol de Neuro_team --}}
            <div class="contacts-list">
                <h2>Chats del Equipo y Estudiantes</h2>
                <ul>
                    <li>Chat con Estudiante A</li>
                    <li>Chat con Estudiante B</li>
                    <li>Chat con el Equipo de Neurocogniciones</li>
                </ul>
            </div>
            
            <div class="chat-area">
                <div class="message-list">
                    <h3>Chat de Neuro_team</h3>
                    <div class="message-item received">Hola, ¿cómo va tu progreso?</div>
                    <div class="message-item sent">Todo bien, ¡gracias por preguntar!</div>
                </div>
                <form class="message-input">
                    <input type="text" placeholder="Escribe un mensaje..." style="flex-grow: 1;">
                    <button type="submit">Enviar</button>
                </form>
            </div>

        @elseif ($userRole === 'estudiante')
            {{-- Interfaz para el rol de Estudiante --}}
            <div class="contacts-list">
                <h2>Mis Contactos</h2>
                <ul>
                    {{-- Aquí se listarán los contactos del estudiante --}}
                    <li>Contacto del Equipo Neuro_team</li>
                    <li>Otros estudiantes</li>
                </ul>
            </div>
            
            <div class="chat-area">
                <div class="message-list">
                    <h3>Mi Chat</h3>
                    <div class="message-item received">Hola, ¿tienes alguna duda sobre el material?</div>
                    <div class="message-item sent">Sí, tengo una pregunta sobre la última lección.</div>
                </div>
                <form class="message-input">
                    <input type="text" placeholder="Escribe un mensaje..." style="flex-grow: 1;">
                    <button type="submit">Enviar</button>
                </form>
            </div>
        @endif
    </div>
@endsection