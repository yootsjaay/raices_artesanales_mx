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
        Ubicacion::create([
            'nombre' => 'Oaxaca de Juárez',
            'tipo' => 'Municipio', // ¡Este sí está permitido!
            'descripcion' => 'Capital de Oaxaca, centro de la vida cultural y artesanal.'
        ]);
        Ubicacion::create([
            'nombre' => 'San Bartolo Coyotepec',
            'tipo' => 'Localidad', // ¡Cambiado a 'Localidad' (o 'Municipio'/'Region')!
            'descripcion' => 'Famoso por su barro negro, técnica prehispánica única.'
        ]);
        Ubicacion::create([
            'nombre' => 'San Martín Tilcajete',
            'tipo' => 'Localidad', // ¡Este también está permitido!
            'descripcion' => 'Cuna de los coloridos alebrijes de madera tallada.'
        ]);
        Ubicacion::create([
            'nombre' => 'Teotitlán del Valle',
            'tipo' => 'Localidad', // ¡Cambiado a 'Localidad' (o 'Municipio'/'Region')!
            'descripcion' => 'Reconocido por sus textiles de lana teñidos con tintes naturales.'
        ]);
    }
}