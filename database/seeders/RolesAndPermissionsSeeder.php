<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear Permisos (opcional pero muy recomendado para granularidad)
        // Aunque tus rutas usan roles, definir permisos te da más flexibilidad a futuro.
        // Por ejemplo, un vendedor podría 'editar_artesanias' pero no 'borrar_artesanias'.

        // Permisos de Artesanías
        Permission::findOrCreate('view_artesanias');
        Permission::findOrCreate('create_artesanias');
        Permission::findOrCreate('edit_artesanias');
        Permission::findOrCreate('delete_artesanias');
        Permission::findOrCreate('import_artesanias');

        // Permisos de Categorías
        Permission::findOrCreate('view_categorias');
        Permission::findOrCreate('create_categorias');
        Permission::findOrCreate('edit_categorias');
        Permission::findOrCreate('delete_categorias');

        // Permisos de Ubicaciones
        Permission::findOrCreate('view_ubicaciones');
        Permission::findOrCreate('create_ubicaciones');
        Permission::findOrCreate('edit_ubicaciones');
        Permission::findOrCreate('delete_ubicaciones');

        // Permisos de Comentarios
        Permission::findOrCreate('manage_comments'); // Ver, aprobar, borrar comentarios
        Permission::findOrCreate('post_comments'); // Publicar comentarios (comprador)

        // Permisos de Usuarios (para el admin o vendedor limitado)
        Permission::findOrCreate('view_users');
        Permission::findOrCreate('edit_users');
        Permission::findOrCreate('delete_users');
        Permission::findOrCreate('assign_roles'); // Solo para administradores

        // Permisos de Carrito/Checkout
        Permission::findOrCreate('manage_cart');
        Permission::findOrCreate('process_checkout');

        // Permisos de Pedidos
        Permission::findOrCreate('view_orders');
        Permission::findOrCreate('manage_orders'); // Cambiar estado, etc.


        // 2. Crear Roles y Asignar Permisos

        // Rol: Admin
        $roleAdmin = Role::findOrCreate('admin');
        $roleAdmin->givePermissionTo(Permission::all()); // El administrador tiene todos los permisos

        // Rol: Vendedor
        $roleVendedor = Role::findOrCreate('vendedor');
        // Un vendedor puede gestionar sus productos, categorías y ubicaciones, y ver/editar usuarios limitados
        $roleVendedor->givePermissionTo([
            'view_artesanias', 'create_artesanias', 'edit_artesanias', 'delete_artesanias', 'import_artesanias',
            'view_categorias', 'create_categorias', 'edit_categorias', 'delete_categorias',
            'view_ubicaciones', 'create_ubicaciones', 'edit_ubicaciones', 'delete_ubicaciones',
            'manage_comments', // Un vendedor podría moderar comentarios sobre sus productos
            'view_users', 'edit_users', // Para el AdminUserController que solo edita y ve
            'view_orders', 'manage_orders', // Un vendedor podría ver y gestionar sus pedidos
        ]);


        // Rol: Comprador
        $roleComprador = Role::findOrCreate('comprador');
        // Un comprador puede ver productos públicos, gestionar su carrito y checkout, y publicar comentarios
        $roleComprador->givePermissionTo([
            'view_artesanias', // Ya cubierto por rutas públicas, pero no hace daño
            'view_categorias',
            'view_ubicaciones',
            'post_comments',
            'manage_cart',
            'process_checkout',
            'view_orders', // Ver sus propios pedidos
        ]);
    
    }
}
