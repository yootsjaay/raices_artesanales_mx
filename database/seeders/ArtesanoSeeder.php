<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesano;
use App\Models\Ubicacion; // Necesario para obtener IDs de ubicación

class ArtesanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que las ubicaciones existan antes de correr este seeder
        // o considera el orden de ejecución en DatabaseSeeder.php
        $oaxacaJuarez = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();
        $coyotepec = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $tilcajete = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $teotitlan = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();

        // Es buena práctica verificar si las ubicaciones existen antes de usarlas
        if (!$oaxacaJuarez || !$coyotepec || !$tilcajete || !$teotitlan) {
            $this->command->info('¡Advertencia! No se encontraron todas las ubicaciones. Asegúrate de ejecutar UbicacionSeeder primero.');
            return; // Detiene el seeder si faltan ubicaciones
        }

        Artesano::create([
            'nombre' => 'Maestro Jacobo y María Ángeles',
            'biografia' => 'Reconocidos a nivel mundial por sus impresionantes alebrijes de madera tallada.',
            'contacto_telefono' => '9511234567', // ¡Corregido el nombre de la columna!
            'contacto_email' => 'jacobo@example.com', // ¡Corregido el nombre de la columna!
            'red_social_facebook' => 'jacobo_maria_angeles_taller',
            'red_social_instagram' => '@jacoboymariaangeles',
            'ubicacion_id' => $tilcajete->id,
        ]);
        Artesano::create([
            'nombre' => 'Familia Aguilar Alfareros',
            'biografia' => 'Maestros del barro negro de Coyotepec, con tradición ancestral transmitida por generaciones.',
            'contacto_telefono' => '9512345678', // ¡Corregido!
            'contacto_email' => 'aguilar@example.com', // ¡Corregido!
            'red_social_facebook' => 'familia_aguilar_alfareros',
            'red_social_instagram' => '@familiaaguilar',
            'ubicacion_id' => $coyotepec->id,
        ]);
        Artesano::create([
            'nombre' => 'Maestra Zapoteca Rufina Ruiz',
            'biografia' => 'Tejedora de renombre de Teotitlán, usa tintes naturales únicos en sus tapetes y rebozos.',
            'contacto_telefono' => '9513456789', // ¡Corregido!
            'contacto_email' => 'rufina@example.com', // ¡Corregido!
            'red_social_facebook' => 'rufina_ruiz_textiles',
            'red_social_instagram' => '@rufinaruiztextiles',
            'ubicacion_id' => $teotitlan->id,
        ]);
        Artesano::create([
            'nombre' => 'Taller de Barro Rojo Doña Rosa',
            'biografia' => 'Preserva la tradición de la alfarería de barro rojo en la ciudad de Oaxaca, con piezas utilitarias y decorativas.',
            'contacto_telefono' => '9514567890', // ¡Corregido!
            'contacto_email' => 'donarosa@example.com', // ¡Corregido!
            'red_social_facebook' => 'barro_rojo_dona_rosa',
            'red_social_instagram' => '@tallerbarrorojo',
            'ubicacion_id' => $oaxacaJuarez->id,
        ]);
    }
}