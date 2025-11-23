<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si el usuario administrador ya existe
        if (!Usuario::where('email', 'anabel.leyva@ecorecicla.com')->exists()) {
            Usuario::create([
                'nombre' => 'Anabel',
                'primerApellido' => 'Leyva',
                'segundoApellido' => null,
                'fechaNacimiento' => null,
                'genero' => 'f',
                'numeroRFID' => null, // RFID único para el admin
                'email' => 'anabel.leyva@ecorecicla.com',
                'password' => Hash::make('admin123'), // Contraseña por defecto
                'role' => 'administrador',
                'puntos' => 0,
                'estado' => 'activo',
            ]);

            $this->command->info('Usuario administrador Anabel Leyva creado exitosamente!');
            $this->command->info('Email: anabel.leyva@ecorecicla.com');
            $this->command->info('Contraseña: admin123');
        } else {
            $this->command->info('El usuario administrador ya existe.');
        }
    }
}
