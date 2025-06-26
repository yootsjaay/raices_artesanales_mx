<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesania;
use App\Models\Categoria;
use App\Models\Ubicacion;
// Si tienes un modelo Artesano, asegúrate de importarlo
// use App\Models\Artesano;

class ArtesaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que los seeders de Categoria y Ubicacion se ejecuten antes.
        // Ejemplo: $this->call(CategoriaSeeder::class);
        // $this->call(UbicacionSeeder::class);

        // Obtener IDs de categorías y ubicaciones existentes
        $alebrijesCat = Categoria::where('nombre', 'Alebrijes')->first();
        $barroNegroCat = Categoria::where('nombre', 'Barro Negro')->first();
        $textilesCat = Categoria::where('nombre', 'Textiles')->first();
        $barroRojoCat = Categoria::where('nombre', 'Barro Rojo')->first();

        $tilcajeteUbi = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $coyotepecUbi = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $teotitlanUbi = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();
        $oaxacaJuarezUbi = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();

        // Validar que las categorías y ubicaciones existen antes de usarlas
        if (!$alebrijesCat || !$barroNegroCat || !$textilesCat || !$barroRojoCat ||
            !$tilcajeteUbi || !$coyotepecUbi || !$teotitlanUbi || !$oaxacaJuarezUbi) {
            $this->command->warn('¡Advertencia! Asegúrate de que CategoriaSeeder y UbicacionSeeder se hayan ejecutado ANTES.');
            $this->command->info('No se crearán artesanías porque faltan categorías o ubicaciones de referencia.');
            return; // Detener la ejecución si faltan datos esenciales
        }


        // NOTA IMPORTANTE:
        // Asegúrate de que las dimensiones (weight, length, width, height)
        // ya incluyan el embalaje individual para cada artesanía.
        // Si la columna 'tecnica_empleada' no existe en tu tabla 'artesanias',
        // necesitarás una migración para añadirla.

        Artesania::firstOrCreate(
            ['nombre' => 'Alebrije de Nahual'], // Criterio para buscar y evitar duplicados
            [
            'descripcion' => 'Impresionante figura de nahual tallada en copal y pintada a mano con intrincados detalles y colores vivos.',
            'precio' => 1250.00,
            'stock' => 5,
            'imagen_principal' => 'images/artesanias/placeholder-alebrije.jpg',
            'imagen_adicionales' => json_encode([
                'images/artesanias/alebrije-nahual-lado.jpg',
                'images/artesanias/alebrije-nahual-detalle.jpg',
            ]),
            'materiales' => 'Madera de copal, pigmentos naturales',
            'historia_piezas' => 'Inspirado en la cosmovisión zapoteca sobre los nahuales.',
            'categoria_id' => $alebrijesCat->id,
            'ubicacion_id' => $tilcajeteUbi->id,
            //'tecnica_empleada' => 'Talla en madera y pintura a mano',
            // --- DATOS DE DIMENSIONES Y PESO (CON EMBALAJE) ---
            'weight' => 1.0, // Peso en KG
            'length' => 30.0, // Largo en CM
            'width' => 20.0, // Ancho en CM
            'height' => 25.0, // Alto en CM
            // -------------------------------------------------
            // 'artesano_id' => $artesano1->id, // Descomentar si tienes artesanos
        ]);

        Artesania::firstOrCreate(
            ['nombre' => 'Cántaro de Barro Negro'],
            [
            'descripcion' => 'Elegante cántaro de barro negro, pulido a mano para lograr su característico brillo metálico y detalles grabados.',
            'precio' => 450.00,
            'stock' => 10,
            'imagen_principal' => 'images/artesanias/barro_placeholder.jpg',
            'imagen_adicionales' => json_encode([
                'images/artesanias/cantaro-barro-negro-pulido.jpg',
                'images/artesanias/cantaro-barro-negro-textura.jpg',
            ]),
            'materiales' => 'Barro negro',
            'historia_piezas' => 'Pieza tradicional de San Bartolo Coyotepec, elaborada con técnicas ancestrales.',
            'categoria_id' => $barroNegroCat->id,
            'ubicacion_id' => $coyotepecUbi->id,
            //'tecnica_empleada' => 'Modelado y pulido de barro',
            // --- DATOS DE DIMENSIONES Y PESO (CON EMBALAJE) ---
            'weight' => 1.5, // Peso en KG
            'length' => 20.0, // Largo en CM
            'width' => 20.0, // Ancho en CM
            'height' => 25.0, // Alto en CM
            // -------------------------------------------------
            // 'artesano_id' => $artesano2->id, // Descomentar si tienes artesanos
        ]);

        Artesania::firstOrCreate(
            ['nombre' => 'Rebozo Zapoteca de Lana'],
            [
            'descripcion' => 'Rebozo de lana tejido en telar de cintura, con patrones zapotecas y tintes naturales a base de grana cochinilla y añil.',
            'precio' => 980.00,
            'stock' => 3,
            'imagen_principal' => 'images/artesanias/textil_placeholder.jpg',
            'imagen_adicionales' => json_encode([
                'images/artesanias/rebozo-detalle-hilo.jpg',
                'images/artesanias/rebozo-en-uso.jpg',
            ]),
            'materiales' => 'Lana de oveja, tintes naturales',
            'historia_piezas' => 'Representa el arduo trabajo y la herencia textil de Teotitlán del Valle.',
            'categoria_id' => $textilesCat->id,
            'ubicacion_id' => $teotitlanUbi->id,
           // 'tecnica_empleada' => 'Telar de cintura y tintes naturales',
            // --- DATOS DE DIMENSIONES Y PESO (CON EMBALAJE) ---
            'weight' => 0.6, // Peso en KG
            'length' => 30.0, // Largo en CM
            'width' => 25.0, // Ancho en CM
            'height' => 5.0, // Alto en CM
            // -------------------------------------------------
            // 'artesano_id' => $artesano3->id, // Descomentar si tienes artesanos
        ]);

        Artesania::firstOrCreate(
            ['nombre' => 'Vajilla de Barro Rojo (4 Piezas)'],
            [
            'descripcion' => 'Set de platos y tazas de barro rojo, cocidos a altas temperaturas, ideales para dar un toque rústico y auténtico a tu mesa.',
            'precio' => 720.00,
            'stock' => 7,
            'imagen_principal' => 'images/artesanias/barro_placeholder.jpg',
            'imagen_adicionales' => json_encode([
                'images/artesanias/vajilla-vista-superior.jpg',
                'images/artesanias/vajilla-copal.jpg',
            ]),
            'materiales' => 'Barro rojo',
            'historia_piezas' => 'Elaborado por alfareros oaxaqueños con tradición en el arte del barro rojo.',
            'categoria_id' => $barroRojoCat->id,
            'ubicacion_id' => $oaxacaJuarezUbi->id,
            //'tecnica_empleada' => 'Alfarería de barro rojo',
            // --- DATOS DE DIMENSIONES Y PESO (CON EMBALAJE) ---
            'weight' => 3.0, // Peso en KG
            'length' => 35.0, // Largo en CM
            'width' => 30.0, // Ancho en CM
            'height' => 20.0, // Alto en CM
            // -------------------------------------------------
            // 'artesano_id' => $artesano4->id, // Descomentar si tienes artesanos
        ]);
    }
}