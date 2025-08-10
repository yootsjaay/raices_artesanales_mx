<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesania;
use App\Models\ArtesaniaVariant;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\TipoEmbalaje;
use Illuminate\Support\Str; // Importar la clase Str para generar slugs

class ArtesaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las dependencias por nombre. Esto es más seguro que usar IDs fijos.
        $alebrijesCat = Categoria::where('nombre', 'Alebrijes')->first();
        $barroNegroCat = Categoria::where('nombre', 'Barro Negro')->first();
        $barroRojoCat = Categoria::where('nombre', 'Barro Rojo')->first();
        $textilesCat = Categoria::where('nombre', 'Textiles')->first();
        $calzadosCat = Categoria::where('nombre', 'Calzados')->first();

        $sanMartin = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $coyotepec = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $oaxaca = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();
        $teotitlan = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();

        $cajaMedianaBarro = TipoEmbalaje::where('nombre', 'Caja Mediana para Barro')->first();
        $cajaGrandeBarro = TipoEmbalaje::where('nombre', 'Caja Grande para Barro')->first();
        $sobreGuayabera = TipoEmbalaje::where('nombre', 'Sobre para Guayabera')->first();
        $cajaPequenaCalzado = TipoEmbalaje::where('nombre', 'Caja Pequeña para Calzado')->first();

        // Validar que todas las dependencias existen antes de continuar
        if (!$alebrijesCat || !$barroNegroCat || !$barroRojoCat || !$textilesCat || !$calzadosCat ||
            !$sanMartin || !$coyotepec || !$oaxaca || !$teotitlan ||
            !$cajaMedianaBarro || !$cajaGrandeBarro || !$sobreGuayabera || !$cajaPequenaCalzado
        ) {
            $this->command->warn('¡Advertencia! Faltan categorías, ubicaciones o tipos de embalaje de referencia. El ArtesaniaSeeder se ha detenido.');
            return;
        }

        // Alebrijes
        $nahualArtesania = Artesania::firstOrCreate(
            ['nombre' => 'Alebrije de Nahual'],
            [
                'slug' => Str::slug('Alebrije de Nahual'),
                'descripcion' => 'Impresionante figura de nahual tallada en copal y pintada a mano con intrincados detalles y colores vivos.',
                'imagen_artesanias' => 'images/artesanias/general/alebrije_nahual_general_1.jpg',
                'historia_piezas_general' => 'Inspirado en la cosmovisión zapoteca sobre los nahuales, cada pieza es única.',
                'precio' => 1500.00, // Precio base
                'categoria_id' => $alebrijesCat->id,
                'ubicacion_id' => $sanMartin->id,
            ]
        );

        ArtesaniaVariant::firstOrCreate(
            ['sku' => ''],
            [
                'artesania_id' => $nahualArtesania->id,
                'variant_name' => 'Alebrije de Nahual - Grande',
                'description_variant' => 'Nahual de gran tamaño con colores vibrantes.',
                'size' => 'Grande',
                'color' => 'Multicolor',
                'material_variant' => 'Madera de Copal',
                'precio' => 1500.00,
                'stock' => 5,
                'imagen_variant' => 'images/artesanias/variantes/alebrije_nahual_1_principal.jpg',
                'tipo_embalaje_id' => $cajaMedianaBarro->id,
                'is_active' => true,
            ]
        );

        // Barro Negro
        $cantaroArtesania = Artesania::firstOrCreate(
            ['nombre' => 'Cántaro de Barro Negro'],
            [
                'slug' => Str::slug('Cántaro de Barro Negro'),
                'descripcion' => 'Cántaro tradicional de barro negro pulido, con grabados de flores y grecas.',
                'imagen_artesanias' => 'images/artesanias/general/cantaro_barro_negro_general_1.jpg',
                'historia_piezas_general' => 'Hecho en San Bartolo Coyotepec, esta técnica data de tiempos prehispánicos y se caracteriza por su brillo metálico.',
                'precio' => 850.00,
                'categoria_id' => $barroNegroCat->id,
                'ubicacion_id' => $coyotepec->id,
            ]
        );

        ArtesaniaVariant::firstOrCreate(
            ['sku' => ''],
            [
                'artesania_id' => $cantaroArtesania->id,
                'variant_name' => 'Cántaro de Barro Negro - Grande',
                'description_variant' => 'Cántaro grande con detalles intrincados.',
                'size' => 'Grande',
                'color' => 'Negro',
                'material_variant' => 'Arcilla de Barro Negro',
                'precio' => 850.00,
                'stock' => 8,
                'imagen_variant' => 'images/artesanias/variantes/cantaro_bn_1_principal.jpg',
                'tipo_embalaje_id' => $cajaGrandeBarro->id,
                'is_active' => true,
            ]
        );
        
        // Textiles
        $huipilArtesania = Artesania::firstOrCreate(
            ['nombre' => 'Huipil Tradicional de Teotitlán'],
            [
                'slug' => Str::slug('Huipil Tradicional de Teotitlán'),
                'descripcion' => 'Huipil de algodón tejido en telar de cintura, con bordados de flores y fauna local.',
                'imagen_artesanias' => 'images/artesanias/general/huipil_teotitlan_general_1.jpg',
                'historia_piezas_general' => 'Elaborado por maestras tejedoras de Teotitlán del Valle, cada huipil cuenta una historia de la comunidad.',
                'precio' => 1200.00,
                'categoria_id' => $textilesCat->id,
                'ubicacion_id' => $teotitlan->id,
            ]
        );

        ArtesaniaVariant::firstOrCreate(
            ['sku' => ''],
            [
                'artesania_id' => $huipilArtesania->id,
                'variant_name' => 'Huipil Rojo con Flores',
                'description_variant' => 'Huipil con fondo rojo intenso y bordados multicolor.',
                'size' => 'Unitalla',
                'color' => 'Rojo',
                'material_variant' => 'Algodón',
                'precio' => 1200.00,
                'stock' => 12,
                'imagen_variant' => 'images/artesanias/variantes/huipil_rojo_1_principal.jpg',
                'tipo_embalaje_id' => $sobreGuayabera->id,
                'is_active' => true,
            ]
        );

        // Calzados
        $huaracheArtesania = Artesania::firstOrCreate(
            ['nombre' => 'Huarache de Cuero Bordado'],
            [
                'slug' => Str::slug('Huarache de Cuero Bordado'),
                'descripcion' => 'Huarache de cuero genuino con correas bordadas a mano, perfectos para cualquier ocasión.',
                'imagen_artesanias' => 'images/artesanias/general/huarache_bordado_general_1.jpg',
                'historia_piezas_general' => 'Este tipo de calzado ha sido parte de la tradición oaxaqueña por generaciones, combinando comodidad y arte.',
                'precio' => 450.00,
                'categoria_id' => $calzadosCat->id,
                'ubicacion_id' => $oaxaca->id,
            ]
        );

        ArtesaniaVariant::firstOrCreate(
            ['sku' => ''],
            [
                'artesania_id' => $huaracheArtesania->id,
                'variant_name' => 'Huarache Talla 25',
                'description_variant' => 'Huarache con bordado rojo, talla 25.',
                'size' => '25',
                'color' => 'Café/Rojo',
                'material_variant' => 'Cuero/Hilo de algodón',
                'precio' => 450.00,
                'stock' => 15,
                'imagen_variant' => 'images/artesanias/variantes/huarache_25_principal.jpg',
                'tipo_embalaje_id' => $cajaPequenaCalzado->id,
                'is_active' => true,
            ]
        );
    }
}
