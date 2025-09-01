<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Neurocogniciones')</title>

    {{-- Enlaza a tu archivo CSS en la carpeta public --}}
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">

    {{-- Si usas otros archivos CSS, enlaza también aquí --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body class="{{ 'role-' . $userRole }}">
    <div class="dashboard-container">
        {{-- Barra Lateral (Sidebar) --}}
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo_neurocogniciones.png') }}" alt="Logo de Neurocogniciones">
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('chat') }}" class="{{ Route::is('chat') ? 'active' : '' }}">Chat</a>
                {{-- Sección solo visible para Admin --}}
                @if($userRole === 'admin')
                <a href="#">Gestión de Usuarios</a>
                <a href="#">Estadísticas</a>
                @endif
                
                {{-- Sección visible para Admin y Neuro_team --}}
                @if($userRole === 'admin' || $userRole === 'neuro_team')
                <a href="#">Configuraciones</a>
                @endif
                <a href="#">Mi Perfil</a>
                <a href="#">Preferencias</a>
                <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
                    @csrf
                    <button type="submit">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
        
        {{-- Contenido Principal --}}
        <div class="main-content">
            <div class="header">
                <h1 class="header-title">Bienvenido, {{ Auth::user()->name }}</h1>
                <div class="header-actions">
                    {{-- Botón para alternar la barra lateral (agrega esta línea) --}}
                    <button id="toggle-sidebar-btn" class="chat-toggle-button">☰</button>
                    <button>Notificaciones</button>
                    <button>Ayuda</button>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>