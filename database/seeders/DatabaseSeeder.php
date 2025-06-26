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
   // Crear un usuario administrador específico
       $this->call(RolesAndPermissionsSeeder::class);

        $this->call([
            UbicacionSeeder::class,
            CategoriaSeeder::class,
            ArtesaniaSeeder::class,
        ]);

          // Usuario Administrador (que también es el vendedor principal)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Busca por email para evitar duplicados
            [
                'name' => 'Admin Vendedor',
                'password' => bcrypt('password'), 
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin'); // Le asignamos el rol 'admin'

        // Primer Usuario Comprador
        $buyer1 = User::firstOrCreate(
            ['email' => 'comprador1@example.com'],
            [
                'name' => 'Comprador Uno',
                'password' => bcrypt('password'), // 
                'email_verified_at' => now(),
            ]
        );
        $buyer1->assignRole('comprador'); // Le asignamos el rol 'comprador'

        // Segundo Usuario Comprador
        $buyer2 = User::firstOrCreate(
            ['email' => 'comprador2@example.com'],
            [
                'name' => 'Comprador Dos',
                'password' => bcrypt('password'), 
                'email_verified_at' => now(),
            ]
        );
        $buyer2->assignRole('comprador'); // Le asignamos el rol 'comprador'

       
    }
}