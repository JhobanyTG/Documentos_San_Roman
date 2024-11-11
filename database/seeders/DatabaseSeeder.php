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
        // Crear personas
        $persona = Persona::create([
            'dni' => '12345678',
            'nombres' => 'Juan',
            'apellido_p' => 'Pérez',
            'apellido_m' => 'Gonzales',
            'f_nacimiento' => '1990-01-01',
            'celular' => '987654321',
            'direccion' => 'Calle Falsa 123',
        ]);

        // Crear privilegios
        Privilegio::insert([
            ['nombre' => 'Acceso Total', 'descripcion' => 'Permite acceso a todas las áreas del sistema.'],
            ['nombre' => 'Acceso a Gerencia', 'descripcion' => 'Tiene toda la funcionalidad de Gerencia, Subgerencia y Documentos.'],
            ['nombre' => 'Acceso a Subgerencia', 'descripcion' => 'Tiene acceso a Subgerencia y a Documentos.'],
            ['nombre' => 'Acceso a Subusuario', 'descripcion' => 'Tiene acceso a Subusuario y a Documentos.'],
            ['nombre' => 'Acceso a Documentos', 'descripcion' => 'Tiene acceso a crear, validar y publicar documentos.'],
            ['nombre' => 'Acceso a Crear Documento', 'descripcion' => 'El usuario puede crear documentos.'],
            ['nombre' => 'Acceso a Validar Documento', 'descripcion' => 'El usuario puede validar el documento.'],
            ['nombre' => 'Acceso a Publicar Documento', 'descripcion' => 'El usuario puede publicar el documento.'],
        ]);

        // Crear roles
        Rol::insert([
            ['nombre' => 'SuperAdmin', 'descripcion' => 'Rol con acceso completo al sistema.'],
            ['nombre' => 'Gerente', 'descripcion' => 'Rol con acceso a funcionalidades de gerencia, subgerencia, subusuarios y documentos.'],
            ['nombre' => 'SubGerente', 'descripcion' => 'Rol con acceso a funcionalidades de subgerencia, subusuarios y documentos.'],
            ['nombre' => 'SubUsuario', 'descripcion' => 'Rol con acceso limitado a crear, validar y publicar documentos.'],
            ['nombre' => 'UsuarioCreador', 'descripcion' => 'Rol con acceso limitado a crear documentos.'],
            ['nombre' => 'UsuarioValidador', 'descripcion' => 'Rol con acceso limitado a validar el documento.'],
            ['nombre' => 'UsuarioPublicador', 'descripcion' => 'Rol con acceso limitado a publicar el documento.'],
        ]);

        // Asociar privilegios a roles
        $rolesPrivilegios = [
            'SuperAdmin' => [
                'Acceso Total',
                'Acceso a Gerencia',
                'Acceso a Subgerencia',
                'Acceso a Subusuario',
                'Acceso a Documentos',
                'Acceso a Crear Documento',
                'Acceso a Validar Documento',
                'Acceso a Publicar Documento',
            ],
            'Gerente' => [
                'Acceso a Gerencia',
                'Acceso a Subgerencia',
                'Acceso a Subusuario',
                'Acceso a Documentos',
                'Acceso a Crear Documento',
                'Acceso a Validar Documento',
                'Acceso a Publicar Documento',
            ],
            'SubGerente' => [
                'Acceso a Gerencia',
                'Acceso a Subgerencia',
                'Acceso a Subusuario',
                'Acceso a Documentos',
                'Acceso a Crear Documento',
                'Acceso a Validar Documento',
                'Acceso a Publicar Documento',
            ],
            'SubUsuario' => [
                'Acceso a Gerencia',
                'Acceso a Documentos',
                'Acceso a Crear Documento',
                'Acceso a Validar Documento',
                'Acceso a Publicar Documento',
            ],
            'UsuarioCreador' => [
                'Acceso a Documentos',
                'Acceso a Crear Documento',
            ],
            'UsuarioValidador' => [
                'Acceso a Documentos',
                'Acceso a Validar Documento',
            ],
            'UsuarioPublicador' => [
                'Acceso a Documentos',
                'Acceso a Publicar Documento',
            ],
        ];

        // Vincular roles con sus privilegios
        foreach ($rolesPrivilegios as $rolNombre => $privilegios) {
            $rol = Rol::where('nombre', $rolNombre)->first();
            foreach ($privilegios as $privilegioNombre) {
                $privilegio = Privilegio::where('nombre', $privilegioNombre)->first();
                RolPrivilegio::create([
                    'rol_id' => $rol->id,
                    'privilegio_id' => $privilegio->id,
                ]);
            }
        }

        // Crear un usuario
        User::create([
            'nombre_usuario' => 'Admin ATI San Roman',
            'email' => 'atisanroman@gmail.com',
            'password' => bcrypt('atisanroman2024'),
            'estado' => 'activo',
            'rol_id' => Rol::where('nombre', 'SuperAdmin')->first()->id,
            'persona_id' => $persona->id,
        ]);
    }
}
