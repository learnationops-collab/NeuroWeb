<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Neurocogniciones')</title>
    <style>
        /* Estilos generales del cuerpo */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; background-color: #f0f2f5; color: #333; }
        
        /* Estilos del contenedor principal */
        .dashboard-container { display: flex; min-height: 100vh; }

        /* Estilos de la barra lateral (sidebar) */
        .sidebar { width: 250px; background-color: #fff; padding: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center; }
        .sidebar-logo { margin-bottom: 20px; }
        .sidebar-logo img { width: 120px; }
        .sidebar-nav a { display: block; padding: 10px 15px; margin-bottom: 10px; border-radius: 8px; text-decoration: none; color: #555; transition: background-color 0.3s; }
        .sidebar-nav a:hover { background-color: #e2e4e6; }
        .sidebar-nav a.active { background-color: #007bff; color: #fff; }

        /* Estilos del contenido principal */
        .main-content { flex-grow: 1; padding: 20px; display: flex; flex-direction: column; }
        .header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        .header-title { font-size: 24px; font-weight: bold; }
        .header-actions { display: flex; gap: 10px; }
        .content-grid { display: grid; gap: 20px; }

        /* Estilos de las tarjetas (cards) */
        .card { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header { font-size: 18px; font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .card-content { line-height: 1.6; color: #666; }

        /* Estilos específicos del dashboard */
        .chat-section { display: flex; flex-direction: column; height: 100%; }
        .contacts-list { max-height: 400px; overflow-y: auto; }
        .wall-section { display: flex; flex-direction: column; }

        /* Columnas del grid */
        .admin-grid { grid-template-columns: repeat(3, 1fr); }
        .neuro-grid { grid-template-columns: repeat(2, 1fr); }
        .student-grid { grid-template-columns: 1fr; }
        
        /* Estilos para los roles */
        .role-admin { background-color: #ffe0e0; }
        .role-neuro { background-color: #e0f0ff; }
        .role-student { background-color: #e0ffe0; }

        /* Responsividad básica */
        @media (max-width: 768px) {
            .dashboard-container { flex-direction: column; }
            .sidebar { width: 100%; height: auto; padding: 10px; }
            .content-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="{{ 'role-' . $userRole }}">
    <div class="dashboard-container">
        {{-- Barra Lateral (Sidebar) --}}
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo_neurocogniciones.png') }}" alt="Logo de Neurocogniciones">
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
                <a href="{{ route('chat') }}">Chat</a>
                <a href="#">Muro</a>
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
            </nav>
            <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
                @csrf
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>

        {{-- Contenido Principal --}}
        <div class="main-content">
            <div class="header">
                <h1 class="header-title">Bienvenido, {{ Auth::user()->name }}</h1>
                <div class="header-actions">
                    <button>Notificaciones</button>
                    <button>Ayuda</button>
                </div>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>