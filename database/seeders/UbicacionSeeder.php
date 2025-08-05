<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ubicacion;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usar firstOrCreate para evitar duplicar las ubicaciones en cada ejecución
        Ubicacion::firstOrCreate(
            ['nombre' => 'Oaxaca de Juárez'],
            [
                'tipo' => 'Municipio',
                'descripcion' => 'Capital de Oaxaca, centro de la vida cultural y artesanal.'
            ]
        );
        
        Ubicacion::firstOrCreate(
            ['nombre' => 'San Bartolo Coyotepec'],
            [
                'tipo' => 'Localidad',
                'descripcion' => 'Famoso por su barro negro, técnica prehispánica única.'
            ]
        );
        
        Ubicacion::firstOrCreate(
            ['nombre' => 'San Martín Tilcajete'],
            [
                'tipo' => 'Localidad',
                'descripcion' => 'Cuna de los coloridos alebrijes de madera tallada.'
            ]
        );
        
        Ubicacion::firstOrCreate(
            ['nombre' => 'Teotitlán del Valle'],
            [
                'tipo' => 'Localidad',
                'descripcion' => 'Reconocido por sus textiles de lana teñidos con tintes naturales.'
            ]
        );
    }
}
