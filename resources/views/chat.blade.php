@extends('layouts.app')

@section('content')
    <div class="chat-container">
        {{-- Lista de contactos --}}
        <div class="contacts-list">
            <h2>Contactos</h2>
            <ul>
                {{-- Aquí irán los contactos cargados dinámicamente --}}
                <li>Contacto 1</li>
                <li>Contacto 2</li>
                <li>...</li>
            </ul>
        </div>
        
        {{-- Área de chat --}}
        <div class="chat-area">
            <div class="message-list">
                {{-- Los mensajes se mostrarán aquí --}}
                <div class="message-item received">Hola, ¿cómo estás?</div>
                <div class="message-item sent">Bien, gracias. ¿Y tú?</div>
            </div>
            
            <form class="message-input">
                <input type="text" placeholder="Escribe un mensaje..." style="flex-grow: 1;">
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
@endsection