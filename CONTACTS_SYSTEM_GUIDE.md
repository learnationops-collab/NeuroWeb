# Sistema de Contactos por Rol - NeuroWeb

## 📋 Descripción General

El sistema de contactos implementa una estructura de comunicación jerárquica basada en roles, donde cada tipo de usuario tiene acceso específico a diferentes contactos según su posición en la organización.

## 🎯 Reglas de Contactos por Rol

### 👨‍🎓 Estudiantes
- **Pueden contactar con**: Equipo NeuroWeb (neuro_team)
- **Propósito**: Obtener ayuda, orientación y soporte académico
- **Secciones visibles**: 
  - 🧠 **Equipo NeuroWeb**: Especialistas y tutores

### 🧠 Equipo NeuroWeb (neuro_team)
- **Pueden contactar con**: Administradores y Estudiantes
- **Propósito**: Coordinar con supervisores y brindar apoyo a estudiantes
- **Secciones visibles**:
  - 👑 **Administradores**: Supervisores del sistema
  - 🎓 **Estudiantes**: Usuarios a los que brindan apoyo

### 👑 Administradores
- **Pueden contactar con**: Equipo NeuroWeb (neuro_team)
- **Propósito**: Supervisar y coordinar actividades del equipo
- **Secciones visibles**:
  - 🧠 **Equipo NeuroWeb**: Personal especializado

## 🛠️ Funcionalidades Implementadas

### Lista de Contactos
- **Ruta**: `/contacts`
- **Vista organizada por secciones** según el rol del usuario
- **Información mostrada**:
  - Avatar con inicial del nombre
  - Nombre completo
  - Email
  - Roles con colores distintivos
  - Botones de acción (Ver Perfil, Chatear)

### Perfil de Contacto
- **Ruta**: `/contacts/{id}`
- **Información detallada**:
  - Avatar y header con gradiente
  - Información de contacto completa
  - Roles y descripción de responsabilidades
  - Acciones disponibles según permisos
  - Estado de actividad
  - Última conexión

### Seguridad y Permisos
- ✅ **Control de acceso**: Solo contactos permitidos por rol
- ✅ **Verificación de permisos**: No se puede acceder a perfiles no autorizados
- ✅ **Error 404**: Para contactos fuera del alcance del usuario

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
- **Grid adaptable**: 1 columna en móvil, 2 en tablet, 3 en desktop
- **Cards con hover effects**: Sombra y transiciones suaves
- **Avatars con gradientes**: Visuales atractivos y únicos

## 🔄 Navegación Integrada

### Sidebar
- Enlace "Contactos" disponible para todos los usuarios autenticados
- Estado activo destacado cuando se está en la sección

### Dashboard
- **Estudiantes**: Sección "Mis Contactos" 
- **Neuro Team**: Sección "Mis Contactos" con descripción de gestión
- **Admin**: Sección "Contactos del Equipo"

### Botones de Acción
- **Ver Perfil**: Navega al detalle completo del contacto
- **💬 Chatear**: Redirige al chat con parámetro de contacto
- **Ver Historial**: Solo para admin y neuro_team
- **Marcar como Favorito**: Funcionalidad placeholder

## 🔧 Implementación Técnica

### Modelo User (app/Models/User.php)
```php
public function getContactsByRole()
{
    // Lógica para filtrar contactos según el rol del usuario
    // Retorna array asociativo con secciones organizadas
}
```

### Controlador (app/Http/Controllers/ContactController.php)
- `index()`: Lista contactos según rol
- `show($id)`: Muestra detalle con verificación de permisos

### Rutas (routes/web.php)
```php
Route::middleware('auth')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
});
```

## 📊 Estado del Sistema

### ✅ Completado
- Lógica de filtrado por roles
- Controladores con seguridad
- Vistas responsive y atractivas
- Navegación integrada
- CSS personalizado
- Control de acceso

### 🔄 Mejoras Futuras Sugeridas
- Búsqueda de contactos
- Filtros adicionales
- Chat en tiempo real integrado
- Notificaciones de conexión
- Historial de interacciones
- Sistema de favoritos funcional
- Estados de disponibilidad (online/offline)

## 🧪 Testing

### Casos de Prueba Recomendados
1. **Acceso por rol**: Verificar que cada rol ve solo sus contactos permitidos
2. **Protección de rutas**: Intentar acceder a perfiles no autorizados
3. **Navegación**: Confirmar enlaces activos y redirecciones
4. **Responsive**: Probar en diferentes tamaños de pantalla
5. **Performance**: Verificar carga con muchos contactos

## 📱 Uso del Sistema

### Para Estudiantes
1. Accede a "Contactos" desde el sidebar o dashboard
2. Ve la lista del "Equipo NeuroWeb" disponible
3. Haz clic en "Ver Perfil" para información detallada
4. Usa "💬 Chatear" para iniciar conversación

### Para Equipo NeuroWeb  
1. Accede a "Contactos" para ver dos secciones
2. "Administradores" para coordinación superior
3. "Estudiantes" para brindar apoyo
4. Acciones adicionales como "Ver Historial"

### Para Administradores
1. Ve "Contactos del Equipo" desde el dashboard
2. Lista completa del equipo NeuroWeb
3. Herramientas de supervisión y coordinación
4. Acceso a funciones administrativas adicionales

El sistema está completamente funcional y listo para uso en producción! 🚀
