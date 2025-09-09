# Neuro Web
## Descripción General del Proyecto

NeuroWeb es una aplicación desarrollada en Laravel 11 que implementa un sistema de autenticación basado en roles, permitiendo tres tipos de usuarios: estudiante, neuro_team y admin. Cada usuario accede a un panel y una interfaz de chat personalizada según su rol, garantizando una experiencia adaptada a sus necesidades.

## Comandos Comunes para el Desarrollo

### Configuración del Entorno
```bash
# Copiar archivo de entorno
cp .env.example .env

# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install

# Generar clave de la aplicación
php artisan key:generate

# Ejecutar migraciones de la base de datos
php artisan migrate

# Poblar la base de datos (incluye configuración de roles)
php artisan db:seed --class=RolesTableSeeder
```

### Servidor de Desarrollo
```bash
# Iniciar servidor de desarrollo de Laravel
php artisan serve

# Iniciar servidor de Vite (para los recursos frontend)
npm run dev

# Compilar recursos para producción
npm run build
```

### Operaciones con la Base de Datos
```bash
# Crear una nueva migración
php artisan make:migration create_table_name

# Ejecutar migraciones
php artisan migrate

# Revertir migraciones
php artisan migrate:rollback

# Poblar una tabla específica
php artisan db:seed --class=SeederName

# Migración fresca con seed
php artisan migrate:fresh --seed
```

### Pruebas
```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar un archivo de prueba específico
php artisan test tests/Feature/ExampleTest.php

# Ejecutar pruebas con reporte de cobertura
php artisan test --coverage
```

### Calidad de Código
```bash
# Formatear código con Laravel Pint
./vendor/bin/pint

# Ejecutar pruebas PHPUnit directamente
./vendor/bin/phpunit
```

### Comandos Artisan
```bash
# Crear un controlador
php artisan make:controller NombreDelControlador

# Crear un modelo con migración
php artisan make:model NombreDelModelo -m

# Crear un seeder
php artisan make:seeder NombreDelSeeder

# Limpiar caché de la aplicación
php artisan cache:clear

# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de vistas
php artisan view:clear
```

## Arquitectura del Proyecto

### Sistema de Autenticación y Autorización
La aplicación utiliza una relación muchos a muchos entre Usuarios y Roles:
- **Modelo Usuario**: Ubicado en `app/Models/User.php`, incluye métodos para gestionar roles.
- **Modelo Rol**: Ubicado en `app/Models/Role.php`, gestiona permisos de usuario.
- **Tabla Pivote**: La tabla `role_user` conecta usuarios con sus roles asignados.
- **Roles Predeterminados**: estudiante, neuro_team y admin (creados mediante el seeder `RolesTableSeeder`).

### Estructura de Controladores
- **AuthController**: Gestiona registro e inicio de sesión (`app/Http/Controllers/Auth/`).
- **DashboardController**: Renderiza el panel según el rol del usuario.
- **ChatController**: Proporciona la interfaz de chat adaptada al rol.
- Todos los controladores extienden la clase base `Controller` y utilizan vistas basadas en roles.

### Diseño de Base de Datos
- Tablas estándar de autenticación de Laravel (users, password_reset_tokens, sessions).
- Tabla personalizada de roles con restricción de nombre único.
- Tabla pivote role_user para la relación muchos a muchos.
- Las migraciones siguen la estructura de clases anónimas de Laravel 11.

### Arquitectura Frontend
- Plantillas Blade en `resources/views/` con herencia de layouts.
- Vite para la compilación de recursos (JavaScript/CSS).
- Renderizado de vistas basado en el rol, pasando userRole a las plantillas.
- Vistas de autenticación que gestionan tanto el login como el registro.

### Estructura de Rutas
- Rutas públicas: `/` (página de autenticación).
- Rutas de autenticación: `/login`, `/register`, `/logout`.
- Rutas protegidas (middleware de autenticación): `/dashboard`, `/chat`.
- Renderizado de contenido según el rol dentro de las rutas protegidas.

### Lógica de Negocio Clave
- Los nuevos usuarios reciben automáticamente el rol 'estudiante' al registrarse.
- Tras autenticarse, el usuario es redirigido al panel con contenido según su rol.
- El rol determina la interfaz y funcionalidades disponibles.
- Gestión de sesiones con regeneración de tokens para mayor seguridad.

### Notas de Desarrollo
- Requiere Laravel 11 y PHP 8.2 o superior.
- El sistema de compilación frontend utiliza Vite en lugar de Laravel Mix.
- La asignación de roles se realiza durante la creación del usuario en RegisterController.
- Todas las verificaciones de roles usan el método `hasRole()` del modelo Usuario para mantener la coherencia.

