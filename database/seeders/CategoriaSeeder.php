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
            'descripcion' => 'Figuras fantásticas talladas en madera y pintadas a mano.'
        ]);
        Categoria::create([
            'nombre' => 'Barro Negro',
            'descripcion' => 'Cerámica de arcilla negra pulida, distintiva de Oaxaca.'
        ]);
        Categoria::create([
            'nombre' => 'Textiles',
            'descripcion' => 'Prendas y objetos de tela bordados o tejidos artesanalmente.'
        ]);
        Categoria::create([
            'nombre' => 'Joyería',
            'descripcion' => 'Piezas únicas de plata y otros materiales con diseños tradicionales.'
        ]);
        Categoria::create([
            'nombre' => 'Alfarería de Barro Rojo',
            'descripcion' => 'Utensilios y objetos decorativos de barro cocido con acabados rústicos.'
        ]);
    }
}
