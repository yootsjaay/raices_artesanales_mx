<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Alebrijes',
            'descripcion' => 'Figuras fantásticas talladas en madera de copal y pintadas con colores vibrantes.',
            'imagen' => 'images/categorias/alebrijes.jpg', // Agrega la ruta aquí
        ]);

        Categoria::create([
            'nombre' => 'Barro Negro', // ¡NUEVA CATEGORÍA AÑADIDA!
            'descripcion' => 'Cerámica de arcilla negra, pulida y quemada para un acabado metálico único.',
            'imagen' => 'images/categorias/barro-negro.jpg', // Asegúrate de tener esta imagen
        ]);

        Categoria::create([
            'nombre' => 'Barro Rojo', // Asegúrate que este es el nombre que quieres usar
            'descripcion' => 'Piezas de cerámica de San Marcos Tlapazola, ideales para cocina y decoración.',
            'imagen' => 'images/categorias/barro-rojo.jpg', // Agrega la ruta aquí
        ]);

        Categoria::create([
            'nombre' => 'Textiles',
            'descripcion' => 'Huipiles, rebozos y tapetes tejidos a mano con diseños tradicionales y modernos.',
            'imagen' => 'images/categorias/textiles.jpg', // Agrega la ruta aquí
        ]);
    }
}