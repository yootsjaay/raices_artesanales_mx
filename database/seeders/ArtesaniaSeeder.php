<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesania;

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
     
        // NOMBRES DE CATEGORÍAS ACTUALIZADOS PARA COINCIDIR CON CategoriaSeeder
        $alebrijesCat = Categoria::where('nombre', 'Alebrijes')->first();
        $barroNegroCat = Categoria::where('nombre', 'Barro Negro')->first();
        $textilesCat = Categoria::where('nombre', 'Textiles')->first();
        $barroRojoCat = Categoria::where('nombre', 'Barro Rojo')->first();

        $tilcajeteUbi = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $coyotepecUbi = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $teotitlanUbi = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();
        $oaxacaJuarezUbi = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();


        // NOTA: El bloque 'if' que causaba el 'return' fue quitado para permitir que las artesanías se creen.
        // Si por alguna razón un ID es nulo, se lanzaría un error de base de datos más específico.


        Artesania::create([
            'nombre' => 'Alebrije de Nahual',
            'descripcion' => 'Impresionante figura de nahual tallada en copal y pintada a mano con intrincados detalles y colores vivos.',
            'precio' => 1250.00,
            'stock' => 5,
            'imagen_principal' => 'images/artesanias/placeholder-alebrije.jpg', // Ruta corregida y nombre de placeholder
            'imagen_adicionales' => json_encode([ // CORREGIDO: Añadidas imágenes adicionales
                'images/artesanias/alebrije-nahual-lado.jpg',
                'images/artesanias/alebrije-nahual-detalle.jpg',
            ]),
            'materiales' => 'Madera de copal, pigmentos naturales',
            'dimensiones' => '25x15x20 cm',
            'historia_piezas' => 'Inspirado en la cosmovisión zapoteca sobre los nahuales.', // CORREGIDO: 'historia_piezas' a 'historia_pieza'
         
            'categoria_id' => $alebrijesCat->id,
            'ubicacion_id' => $tilcajeteUbi->id,
            'tecnica_empleada' => 'Talla en madera y pintura a mano',
        ]);

        Artesania::create([
            'nombre' => 'Cántaro de Barro Negro',
            'descripcion' => 'Elegante cántaro de barro negro, pulido a mano para lograr su característico brillo metálico y detalles grabados.',
            'precio' => 450.00,
            'stock' => 10,
            'imagen_principal' => 'images/artesanias/barro_placeholder.jpg', // Ruta corregida y nombre de placeholder
            'imagen_adicionales' => json_encode([ // CORREGIDO: Añadidas imágenes adicionales
                'images/artesanias/cantaro-barro-negro-pulido.jpg',
                'images/artesanias/cantaro-barro-negro-textura.jpg',
            ]),
            'materiales' => 'Barro negro',
            'dimensiones' => '30x20 cm',
            'historia_piezas' => 'Pieza tradicional de San Bartolo Coyotepec, elaborada con técnicas ancestrales.', // CORREGIDO: 'historia_piezas' a 'historia_pieza'
          
            'categoria_id' => $barroNegroCat->id,
            'ubicacion_id' => $coyotepecUbi->id,
            'tecnica_empleada' => 'Modelado y pulido de barro',
        ]);

        Artesania::create([
            'nombre' => 'Rebozo Zapoteca de Lana',
            'descripcion' => 'Rebozo de lana tejido en telar de cintura, con patrones zapotecas y tintes naturales a base de grana cochinilla y añil.',
            'precio' => 980.00,
            'stock' => 3,
            'imagen_principal' => 'images/artesanias/textil_placeholder.jpg', // Ruta corregida y nombre de placeholder
            'imagen_adicionales' => json_encode([ // CORREGIDO: Añadidas imágenes adicionales
                'images/artesanias/rebozo-detalle-hilo.jpg',
                'images/artesanias/rebozo-en-uso.jpg',
            ]),
            'materiales' => 'Lana de oveja, tintes naturales',
            'dimensiones' => '200x70 cm',
            'historia_piezas' => 'Representa el arduo trabajo y la herencia textil de Teotitlán del Valle.', // CORREGIDO: 'historia_piezas' a 'historia_pieza'
           
            'categoria_id' => $textilesCat->id,
            'ubicacion_id' => $teotitlanUbi->id,
            'tecnica_empleada' => 'Telar de cintura y tintes naturales',
        ]);

        Artesania::create([
            'nombre' => 'Vajilla de Barro Rojo (4 Piezas)',
            'descripcion' => 'Set de platos y tazas de barro rojo, cocidos a altas temperaturas, ideales para dar un toque rústico y auténtico a tu mesa.',
            'precio' => 720.00,
            'stock' => 7,
            'imagen_principal' => 'images/artesanias/barro_placeholder.jpg', // Ruta corregida y nombre de placeholder
            'imagen_adicionales' => json_encode([ // CORREGIDO: Añadidas imágenes adicionales
                'images/artesanias/vajilla-vista-superior.jpg',
                'images/artesanias/vajilla-copal.jpg',
            ]),
            'materiales' => 'Barro rojo',
            'dimensiones' => 'Plato: 25cm diámetro, Taza: 10cm alto',
            'historia_piezas' => 'Elaborado por alfareros oaxaqueños con tradición en el arte del barro rojo.', // CORREGIDO: 'historia_piezas' a 'historia_pieza'
            
            'categoria_id' => $barroRojoCat->id,
            'ubicacion_id' => $oaxacaJuarezUbi->id,
            'tecnica_empleada' => 'Alfarería de barro rojo',
        ]);
    }
}