<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutar seeders de dependencias primero (roles, ubicaciones, categorías, tipos de embalaje)
        // Esto asegura que los datos referenciados existan antes de que se creen las artesanías.
        $this->call(RolesAndPermissionsSeeder::class); // Si los roles son una dependencia para los usuarios
        $this->call(UbicacionSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(TipoEmbalajeSeeder::class); // ¡Asegúrate de que este seeder exista!

        // 2. Ejecutar el seeder de Artesanías (que depende de los anteriores)
        $this->call(ArtesaniaSeeder::class);

        // 3. Crear usuarios (después de que los roles estén definidos, si RolesAndPermissionsSeeder los crea)
        // Usuario Administrador (que también es el vendedor principal)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Busca por email para evitar duplicados
            [
                'name' => 'Admin Vendedor',
                'password' => Hash::make('password'), // Usa Hash::make() para bcrypt
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin'); // Le asignamos el rol 'admin'

        // Primer Usuario Comprador
        $buyer1 = User::firstOrCreate(
            ['email' => 'comprador1@example.com'],
            [
                'name' => 'Comprador Uno',
                'password' => Hash::make('password'), // Usa Hash::make() para bcrypt
                'email_verified_at' => now(),
            ]
        );
        $buyer1->assignRole('comprador'); // Le asignamos el rol 'comprador'

        // Segundo Usuario Comprador
        $buyer2 = User::firstOrCreate(
            ['email' => 'comprador2@example.com'],
            [
                'name' => 'Comprador Dos',
                'password' => Hash::make('password'), // Usa Hash::make() para bcrypt
                'email_verified_at' => now(),
            ]
        );
        $buyer2->assignRole('comprador'); // Le asignamos el rol 'comprador'
    }
}
