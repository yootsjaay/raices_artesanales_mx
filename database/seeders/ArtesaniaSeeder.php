<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesania;
use App\Models\Artesano;
use App\Models\Categoria;
use App\Models\Ubicacion;

class ArtesaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de artesanos, categorías y ubicaciones
        // Asegúrate de que los seeders de Ubicacion, Categoria y Artesano se ejecuten antes.
        $jacobo = Artesano::where('nombre', 'Maestro Jacobo y María Ángeles')->first();
        $aguilar = Artesano::where('nombre', 'Familia Aguilar Alfareros')->first();
        $rufina = Artesano::where('nombre', 'Maestra Zapoteca Rufina Ruiz')->first();
        $donaRosa = Artesano::where('nombre', 'Taller de Barro Rojo Doña Rosa')->first();

        $alebrijesCat = Categoria::where('nombre', 'Alebrijes')->first();
        $barroNegroCat = Categoria::where('nombre', 'Barro Negro')->first();
        $textilesCat = Categoria::where('nombre', 'Textiles')->first();
        $barroRojoCat = Categoria::where('nombre', 'Alfarería de Barro Rojo')->first();

        $tilcajeteUbi = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $coyotepecUbi = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $teotitlanUbi = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();
        $oaxacaJuarezUbi = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();


        // --- Verifica que los datos necesarios existen ---
        if (!$jacobo || !$aguilar || !$rufina || !$donaRosa ||
            !$alebrijesCat || !$barroNegroCat || !$textilesCat || !$barroRojoCat ||
            !$tilcajeteUbi || !$coyotepecUbi || !$teotitlanUbi || !$oaxacaJuarezUbi) {
            $this->command->info('¡Advertencia! Faltan datos en seeders previos. Asegúrate de ejecutar UbicacionSeeder, CategoriaSeeder y ArtesanoSeeder antes.');
            return;
        }


        Artesania::create([
            'nombre' => 'Alebrije de Nahual',
            'descripcion' => 'Impresionante figura de nahual tallada en copal y pintada a mano con intrincados detalles y colores vivos.',
            'precio' => 1250.00,
            'stock' => 5,
            'imagen_principal' => 'images/placeholder-alebrije.jpg',
            'imagen_adicionales' => null, // O un JSON string si manejas múltiples imágenes: '["img1.jpg", "img2.jpg"]'
            'materiales' => 'Madera de copal, pigmentos naturales',
            'dimensiones' => '25x15x20 cm',
            'historia_piezas' => 'Inspirado en la cosmovisión zapoteca sobre los nahuales.',
            'artesano_id' => $jacobo->id,     // ¡CORREGIDO: artesanos_id a artesano_id!
            'categoria_id' => $alebrijesCat->id, // ¡CORREGIDO: categorias_id a categoria_id!
            'ubicacion_id' => $tilcajeteUbi->id,
        ]);

        Artesania::create([
            'nombre' => 'Cántaro de Barro Negro',
            'descripcion' => 'Elegante cántaro de barro negro, pulido a mano para lograr su característico brillo metálico y detalles grabados.',
            'precio' => 450.00,
            'stock' => 10,
            'imagen_principal' => 'images/placeholder-barro-negro.jpg',
            'imagen_adicionales' => null,
            'materiales' => 'Barro negro',
            'dimensiones' => '30x20 cm',
            'historia_piezas' => 'Pieza tradicional de San Bartolo Coyotepec, elaborada con técnicas ancestrales.',
            'artesano_id' => $aguilar->id,
            'categoria_id' => $barroNegroCat->id,
            'ubicacion_id' => $coyotepecUbi->id,
        ]);

        Artesania::create([
            'nombre' => 'Rebozo Zapoteca de Lana',
            'descripcion' => 'Rebozo de lana tejido en telar de cintura, con patrones zapotecas y tintes naturales a base de grana cochinilla y añil.',
            'precio' => 980.00,
            'stock' => 3,
            'imagen_principal' => 'images/placeholder-rebozo.jpg',
            'imagen_adicionales' => null,
            'materiales' => 'Lana de oveja, tintes naturales',
            'dimensiones' => '200x70 cm',
            'historia_piezas' => 'Representa el arduo trabajo y la herencia textil de Teotitlán del Valle.',
            'artesano_id' => $rufina->id,
            'categoria_id' => $textilesCat->id,
            'ubicacion_id' => $teotitlanUbi->id,
        ]);

        Artesania::create([
            'nombre' => 'Vajilla de Barro Rojo (4 Piezas)',
            'descripcion' => 'Set de platos y tazas de barro rojo, cocidos a altas temperaturas, ideales para dar un toque rústico y auténtico a tu mesa.',
            'precio' => 720.00,
            'stock' => 7,
            'imagen_principal' => 'images/placeholder-barro-rojo.jpg',
            'imagen_adicionales' => null,
            'materiales' => 'Barro rojo',
            'dimensiones' => 'Plato: 25cm diámetro, Taza: 10cm alto',
            'historia_piezas' => 'Elaborado por alfareros oaxaqueños con tradición en el arte del barro rojo.',
            'artesano_id' => $donaRosa->id,
            'categoria_id' => $barroRojoCat->id,
            'ubicacion_id' => $oaxacaJuarezUbi->id,
        ]);
    }
}