<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Asegúrate de que esta línea esté presente
use Illuminate\Support\Facades\Hash; // <-- Asegúrate de que esta línea esté presente

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Puedes mantener esto si quieres usuarios de prueba adicionales

        // Crear un usuario administrador específico
        User::create([
            'name' => 'Admin',
            'email' => 'admin@raices.com', // Puedes cambiar el email
            'password' => Hash::make('password'), // ¡CAMBIA ESTA CONTRASEÑA POR UNA SEGURA EN PRODUCCIÓN!
            // 'email_verified_at' => now(), // Descomenta si quieres que esté verificado automáticamente
        ]);

        $this->call([
            UbicacionSeeder::class,
            CategoriaSeeder::class,
            ArtesaniaSeeder::class,
        ]);
    }
}