@extends('layouts.app')

@section('content')
    {{-- Contenido específico según el rol --}}
    @if($userRole === 'admin')
        <div class="content-grid admin-grid">
            <div class="card chat-section">
                <div class="card-header">Chat (Todas las Conversaciones)</div>
                <div class="card-content">
                    <p>Lista de todas las conversaciones. El administrador puede filtrar y ver todas las interacciones.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Estadísticas y Gráficos</div>
                <div class="card-content">
                    <p>Gráficos de uso, número de usuarios, rendimiento, etc.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Muro (Gestión de Publicaciones)</div>
                <div class="card-content">
                    <p>Vista del muro para publicar anuncios, información y gestionar el contenido.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Control de Usuarios</div>
                <div class="card-content">
                    <p>Herramientas para agregar, editar y eliminar usuarios. Asignación de roles.</p>
                </div>
            </div>
        </div>

    @elseif($userRole === 'neuro_team')
        <div class="content-grid neuro-grid">
            <div class="card chat-section">
                <div class="card-header">Chat</div>
                <div class="card-content">
                    <p>Conversaciones con estudiantes y otros miembros del equipo.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Muro</div>
                <div class="card-content">
                    <p>Muro para ver y crear publicaciones para los estudiantes.</p>
                </div>
            </div>
        </div>

    @elseif($userRole === 'estudiante')
        <div class="content-grid student-grid">
            <div class="card chat-section">
                <div class="card-header">Mis Conversaciones</div>
                <div class="card-content">
                    <p>Acceso a chats individuales y grupales con el equipo y otros estudiantes.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Mi Muro</div>
                <div class="card-content">
                    <p>Sección para ver publicaciones, anuncios y material de estudio.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Mi Perfil</div>
                <div class="card-content">
                    <p>Aquí se podrá ver y editar la información de tu perfil personal.</p>
                </div>
            </div>
        </div>
    @endif
@endsection