<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::firstOrCreate(
            ['nombre' => 'Alebrijes'],
            [
                'descripcion' => 'Figuras fantásticas talladas en madera de copal y pintadas con colores vibrantes.',
                'imagen' => 'images/categorias/alebrijes.jpg',
            ]
        );

        Categoria::firstOrCreate(
            ['nombre' => 'Barro Negro'],
            [
                'descripcion' => 'Cerámica de arcilla negra, pulida y quemada para un acabado metálico único.',
                'imagen' => 'images/categorias/barro-negro.jpg',
            ]
        );

        Categoria::firstOrCreate(
            ['nombre' => 'Barro Rojo'],
            [
                'descripcion' => 'Piezas de cerámica de San Marcos Tlapazola, ideales para cocina y decoración.',
                'imagen' => 'images/categorias/barro-rojo.jpg',
            ]
        );

        Categoria::firstOrCreate(
            ['nombre' => 'Textiles'],
            [
                'descripcion' => 'Huipiles, rebozos y tapetes tejidos a mano con diseños tradicionales y modernos.',
                'imagen' => 'images/categorias/textiles.jpg',
            ]
        );
        
        Categoria::firstOrCreate(
            ['nombre' => 'Calzados'],
            [
                'descripcion' => 'Huaraches, sandalias con bordados tradicionales de la región.',
                'imagen' => 'images/categorias/calzados.jpg',
            ]
        );
    }
}
