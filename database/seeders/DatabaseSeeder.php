<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\Privilegio;
use App\Models\RolPrivilegio;
use App\Models\Persona;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear una persona
        $persona = Persona::create([
            'dni' => '12345678',
            'nombres' => 'Juan',
            'apellido_p' => 'Pérez',
            'apellido_m' => 'Gonzales',
            'f_nacimiento' => '1990-01-01',
            'celular' => '987654321',
            'direccion' => 'Calle Falsa 123',
        ]);

        // Crear un privilegio
        $privilegio = Privilegio::create([
            'nombre' => 'Acceso Total',
            'descripcion' => 'Permite acceso a todas las áreas del sistema.',
        ]);

        // Crear un rol
        $rol = Rol::create([
            'nombre' => 'SuperAdmin',
            'descripcion' => 'Rol con acceso completo al sistema.',
        ]);

        // Asociar el privilegio al rol
        RolPrivilegio::create([
            'privilegio_id' => $privilegio->id,
            'rol_id' => $rol->id,
        ]);

        // Crear un usuario
        User::create([
            'nombre_usuario' => 'Admin ATI San Roman',
            'email' => 'atisanroman@gmail.com',
            'password' => bcrypt('atisanroman2024'),
            'estado' => 'activo',
            'rol_id' => $rol->id,
            'persona_id' => $persona->id,
        ]);
    }
}
