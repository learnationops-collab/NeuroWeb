# Sistema de Contactos por Rol - NeuroWeb

## 📋 Descripción General

El sistema de contactos de NeuroWeb organiza la comunicación interna según el rol de cada usuario, permitiendo que cada tipo de usuario acceda únicamente a los contactos que le corresponden dentro de la estructura de la organización. Esto garantiza seguridad, claridad y eficiencia en la gestión de la información y el soporte.

## 🎯 Reglas de Contactos por Rol

### 👨‍🎓 Estudiantes
- **Contactos disponibles**: Solo pueden comunicarse con el Equipo NeuroWeb (neuro_team).
- **Propósito**: Solicitar ayuda, orientación académica y soporte técnico.
- **Secciones visibles**: 
  - 🧠 **Equipo NeuroWeb**: Especialistas y tutores que brindan apoyo directo.

### 🧠 Equipo NeuroWeb (neuro_team)
- **Contactos disponibles**: Pueden contactar tanto a Administradores como a Estudiantes.
- **Propósito**: Coordinar tareas con los supervisores y ofrecer asistencia a los estudiantes.
- **Secciones visibles**:
  - 👑 **Administradores**: Supervisores responsables del sistema.
  - 🎓 **Estudiantes**: Usuarios que reciben apoyo y seguimiento.

### 👑 Administradores
- **Contactos disponibles**: Tienen acceso al Equipo NeuroWeb (neuro_team).
- **Propósito**: Supervisar, coordinar y gestionar las actividades del equipo.
- **Secciones visibles**:
  - 🧠 **Equipo NeuroWeb**: Personal especializado encargado de la operación diaria.

## 🛠️ Funcionalidades Implementadas

### Lista de Contactos
- **Ruta**: `/contacts`
- **Vista personalizada**: La lista se organiza en secciones según el rol del usuario.
- **Información mostrada**:
  - Avatar con la inicial del nombre
  - Nombre completo
  - Correo electrónico
  - Rol destacado con color específico
  - Botones de acción: Ver Perfil y Chatear

### Perfil de Contacto
- **Ruta**: `/contacts/{id}`
- **Información detallada**:
  - Avatar y encabezado con gradiente de color
  - Datos completos de contacto
  - Roles y descripción de responsabilidades
  - Acciones disponibles según permisos del usuario
  - Estado de actividad y última conexión

### Seguridad y Permisos
- ✅ **Control de acceso**: Solo se muestran los contactos permitidos según el rol.
- ✅ **Verificación de permisos**: No es posible acceder a perfiles no autorizados.
- ✅ **Error 404**: Se muestra si se intenta acceder a un contacto fuera del alcance permitido.

## 🎨 Interfaz de Usuario

### Diseño Visual
- **Colores por rol**:
  - 🔴 **Admin**: Rojo (#dc2626)
  - 🔵 **Neuro Team**: Azul (#2563eb)
  - ⚫ **Estudiante**: Gris (#4b5563)

- **Iconos identificativos**:
  - 👑 Administradores
  - 🧠 Equipo NeuroWeb  
  - 🎓 Estudiantes

### Características Responsivas
- **Grid adaptable**: La vista se ajusta automáticamente; una columna en móvil, dos en tablet y tres en escritorio.
- **Tarjetas con efectos hover**: Sombra y transiciones suaves para mejorar la experiencia visual.
- **Avatares con gradientes**: Imágenes atractivas y personalizadas para cada usuario.

## 🔄 Navegación Integrada

### Sidebar
- El enlace "Contactos" está disponible para todos los usuarios autenticados.
- El estado activo se resalta cuando el usuario está en la sección de contactos.

### Dashboard
- **Estudiantes**: Acceso a la sección "Mis Contactos".
- **Neuro Team**: Sección "Mis Contactos" con información sobre gestión y apoyo.
- **Admin**: Sección "Contactos del Equipo" para supervisión y coordinación.

### Botones de Acción
- **Ver Perfil**: Permite ver la información completa del contacto.
- **💬 Chatear**: Redirige al chat con el contacto seleccionado.
- **Ver Historial**: Disponible solo para administradores y neuro_team.
- **Marcar como Favorito**: Funcionalidad en desarrollo.

## 🔧 Implementación Técnica

### Modelo User (app/Models/User.php)
```php
public function getContactsByRole()
{
    // Filtra y organiza los contactos según el rol del usuario actual.
    // Devuelve un array con las secciones correspondientes.
}
```

### Controlador (app/Http/Controllers/ContactController.php)
- `index()`: Muestra la lista de contactos filtrada por rol.
- `show($id)`: Presenta el detalle del contacto, verificando permisos de acceso.

### Rutas (routes/web.php)
```php
Route::middleware('auth')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
});
```

## 📊 Estado del Sistema

### ✅ Completado
- Filtrado de contactos por rol
- Controladores con seguridad y validación
- Vistas responsivas y atractivas
- Navegación integrada en la interfaz
- Estilos CSS personalizados
- Control de acceso y permisos

### 🔄 Mejoras Futuras Sugeridas
- Búsqueda avanzada de contactos
- Filtros adicionales por criterios
- Chat en tiempo real integrado
- Notificaciones de conexión y actividad
- Historial de interacciones entre usuarios
- Sistema de favoritos funcional
- Estados de disponibilidad (online/offline)

## 🧪 Testing

### Casos de Prueba Recomendados
1. **Acceso por rol**: Verificar que cada usuario solo ve los contactos permitidos.
2. **Protección de rutas**: Intentar acceder a perfiles no autorizados y comprobar la restricción.
3. **Navegación**: Confirmar que los enlaces y redirecciones funcionan correctamente.
4. **Responsive**: Probar la visualización en diferentes dispositivos y tamaños de pantalla.
5. **Performance**: Evaluar el rendimiento con una gran cantidad de contactos.

## 📱 Uso del Sistema

### Para Estudiantes
1. Accede a "Contactos" desde el menú lateral o el dashboard.
2. Visualiza la lista del "Equipo NeuroWeb" disponible para soporte.
3. Haz clic en "Ver Perfil" para obtener información detallada.
4. Usa "💬 Chatear" para iniciar una conversación directa.

### Para Equipo NeuroWeb  
1. Ingresa a "Contactos" para ver las secciones de Administradores y Estudiantes.
2. Utiliza la sección de "Administradores" para coordinar tareas y recibir instrucciones.
3. Accede a la sección de "Estudiantes" para brindar apoyo y seguimiento.
4. Realiza acciones adicionales como consultar el historial de interacciones.

### Para Administradores
1. Accede a "Contactos del Equipo" desde el dashboard.
2. Visualiza la lista completa del equipo NeuroWeb.
3. Utiliza herramientas de supervisión y coordinación.
4. Accede a funciones administrativas avanzadas según permisos.

El sistema está completamente operativo y listo para ser utilizado en producción. ¡Facilita la comunicación y la gestión dentro de NeuroWeb! 🚀
