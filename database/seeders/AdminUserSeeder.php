<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan los roles
        $adminRole = Role::where('name', 'admin')->first();
        $estudianteRole = Role::where('name', 'estudiante')->first();
        $neuroTeamRole = Role::where('name', 'neuro_team')->first();

        if (!$adminRole || !$estudianteRole || !$neuroTeamRole) {
            $this->command->info('Los roles no existen. Ejecuta primero: php artisan db:seed --class=RolesTableSeeder');
            return;
        }

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@neuroweb.com'],
            [
                'name' => 'Administrador NeuroWeb',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asignar rol de admin
        if (!$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($adminRole->id);
            $this->command->info('Usuario administrador creado: admin@neuroweb.com / admin123');
        } else {
            $this->command->info('El usuario administrador ya existe.');
        }

        // Crear usuario del equipo neuro para pruebas
        $neuroUser = User::firstOrCreate(
            ['email' => 'neuro@neuroweb.com'],
            [
                'name' => 'Miembro Neuro Team',
                'password' => Hash::make('neuro123'),
            ]
        );

        // Asignar rol de neuro_team
        if (!$neuroUser->roles()->where('name', 'neuro_team')->exists()) {
            $neuroUser->roles()->attach($neuroTeamRole->id);
            $this->command->info('Usuario neuro team creado: neuro@neuroweb.com / neuro123');
        }

        // Crear usuario estudiante para pruebas
        $student = User::firstOrCreate(
            ['email' => 'estudiante@neuroweb.com'],
            [
                'name' => 'Estudiante de Prueba',
                'password' => Hash::make('estudiante123'),
            ]
        );

        // Asignar rol de estudiante
        if (!$student->roles()->where('name', 'estudiante')->exists()) {
            $student->roles()->attach($estudianteRole->id);
            $this->command->info('Usuario estudiante creado: estudiante@neuroweb.com / estudiante123');
        }
    }
}
