<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoEmbalaje;

class TipoEmbalajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se usan valores de ejemplo para peso y dimensiones.
        // Se usa firstOrCreate para evitar duplicados si el seeder se ejecuta varias veces.

        TipoEmbalaje::firstOrCreate(
            ['nombre' => 'Sobre para Guayabera'],
            [
                'descripcion' => 'Sobre de empaque plano, ideal para textiles como rebozos o guayaberas.',
                'weight' => 0.05,
                'length' => 40.00,
                'width' => 30.00,
                'height' => 1.00,
                'is_active' => true,
            ]
        );

        TipoEmbalaje::firstOrCreate(
            ['nombre' => 'Caja Pequeña para Calzado'],
            [
                'descripcion' => 'Caja de cartón pequeña, adecuada para figuras de barro o calzado de peso ligero.',
                'weight' => 0.20,
                'length' => 25.00,
                'width' => 15.00,
                'height' => 10.00,
                'is_active' => true,
            ]
        );

        TipoEmbalaje::firstOrCreate(
            ['nombre' => 'Caja Mediana para Barro'],
            [
                'descripcion' => 'Caja robusta de tamaño mediano, perfecta para cántaros o alebrijes medianos.',
                'weight' => 0.50,
                'length' => 35.00,
                'width' => 25.00,
                'height' => 20.00,
                'is_active' => true,
            ]
        );
        
        TipoEmbalaje::firstOrCreate(
            ['nombre' => 'Caja Grande para Barro'],
            [
                'descripcion' => 'Caja grande y reforzada, ideal para vajillas o piezas de barro más grandes.',
                'weight' => 0.80,
                'length' => 50.00,
                'width' => 40.00,
                'height' => 30.00,
                'is_active' => true,
            ]
        );
    }
}
