<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artesania;
use App\Models\ArtesaniaVariant;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\TipoEmbalaje;
use App\Models\Atributo;
use App\Models\AtributoArtesaniaVariant;
use Illuminate\Support\Str;

class ArtesaniaSeeder extends Seeder
{
    public function run(): void
    {
        // --- Categorías, Ubicaciones, Tipos de embalaje ---
        $alebrijesCat = Categoria::where('nombre', 'Alebrijes')->first();
        $barroNegroCat = Categoria::where('nombre', 'Barro Negro')->first();
        $textilesCat = Categoria::where('nombre', 'Textiles')->first();
        $calzadosCat = Categoria::where('nombre', 'Calzados')->first();

        $sanMartin = Ubicacion::where('nombre', 'San Martín Tilcajete')->first();
        $coyotepec = Ubicacion::where('nombre', 'San Bartolo Coyotepec')->first();
        $oaxaca = Ubicacion::where('nombre', 'Oaxaca de Juárez')->first();
        $teotitlan = Ubicacion::where('nombre', 'Teotitlán del Valle')->first();

        $sobrePequeno = TipoEmbalaje::where('nombre', 'Sobre pequeño')->first();
        $cajaChica = TipoEmbalaje::where('nombre', 'Caja chica')->first();
        $cajaMediana = TipoEmbalaje::where('nombre', 'Caja mediana')->first();
        $cajaGrande = TipoEmbalaje::where('nombre', 'Caja grande')->first();
        $cajaExtra = TipoEmbalaje::where('nombre', 'Caja extra')->first(); // Opcional, si lo vas a usar

        if (!$alebrijesCat || !$barroNegroCat || !$textilesCat || !$calzadosCat ||
            !$sanMartin || !$coyotepec || !$oaxaca || !$teotitlan ||
            !$cajaMediana || !$cajaGrande || !$sobrePequeno || !$cajaChica
        ) {
            $this->command->warn('¡Advertencia! Faltan categorías, ubicaciones o tipos de embalaje.');
            return;
        }

        // --- Crear atributos si no existen ---
        $tallaAttr = Atributo::firstOrCreate(['nombre' => 'Talla']);
        $colorAttr = Atributo::firstOrCreate(['nombre' => 'Color']);
        $materialAttr = Atributo::firstOrCreate(['nombre' => 'Material']);

        // --- Productos ---
        $productos = [
            [
                'nombre' => 'Alebrije de Nahual',
                'descripcion' => 'Figura de nahual en copal pintada a mano.',
                'imagen_general' => 'images/artesanias/general/alebrije_nahual_general_1.jpg',
                'historia' => 'Inspirado en la cosmovisión zapoteca.',
                'precio' => 1500.00,
                'categoria' => $alebrijesCat,
                'ubicacion' => $sanMartin,
                'variant_name' => 'Alebrije de Nahual - Grande',
                'sku' => Str::upper(Str::random(8)),
                'stock' => 5,
                'imagen_variant' => 'images/artesanias/variantes/alebrije_nahual_1_principal.jpg',
                'tipo_embalaje' => $cajaMediana,
                'atributos' => [
                    ['atributo' => $tallaAttr, 'valor' => 'Grande'],
                    ['atributo' => $colorAttr, 'valor' => 'Multicolor'],
                    ['atributo' => $materialAttr, 'valor' => 'Madera de Copal'],
                ]
            ],
            [
                'nombre' => 'Cántaro de Barro Negro',
                'descripcion' => 'Cántaro tradicional de barro negro pulido.',
                'imagen_general' => 'images/artesanias/general/cantaro_barro_negro_general_1.jpg',
                'historia' => 'Hecho en San Bartolo Coyotepec.',
                'precio' => 850.00,
                'categoria' => $barroNegroCat,
                'ubicacion' => $coyotepec,
                'variant_name' => 'Cántaro de Barro Negro - Grande',
                'sku' => Str::upper(Str::random(8)),
                'stock' => 8,
                'imagen_variant' => 'images/artesanias/variantes/cantaro_bn_1_principal.jpg',
                'tipo_embalaje' => $cajaGrande,
                'atributos' => [
                    ['atributo' => $tallaAttr, 'valor' => 'Grande'],
                    ['atributo' => $colorAttr, 'valor' => 'Negro'],
                    ['atributo' => $materialAttr, 'valor' => 'Arcilla de Barro Negro'],
                ]
            ],
            [
                'nombre' => 'Huipil Tradicional de Teotitlán',
                'descripcion' => 'Huipil de algodón tejido en telar de cintura.',
                'imagen_general' => 'images/artesanias/general/huipil_teotitlan_general_1.jpg',
                'historia' => 'Elaborado por maestras tejedoras de Teotitlán del Valle.',
                'precio' => 1200.00,
                'categoria' => $textilesCat,
                'ubicacion' => $teotitlan,
                'variant_name' => 'Huipil Rojo con Flores',
                'sku' => Str::upper(Str::random(8)),
                'stock' => 12,
                'imagen_variant' => 'images/artesanias/variantes/huipil_rojo_1_principal.jpg',
                'tipo_embalaje' => $sobrePequeno,
                'atributos' => [
                    ['atributo' => $tallaAttr, 'valor' => 'Unitalla'],
                    ['atributo' => $colorAttr, 'valor' => 'Rojo'],
                    ['atributo' => $materialAttr, 'valor' => 'Algodón'],
                ]
            ],
            [
                'nombre' => 'Huarache de Cuero Bordado',
                'descripcion' => 'Huarache de cuero genuino con correas bordadas a mano.',
                'imagen_general' => 'images/artesanias/general/huarache_bordado_general_1.jpg',
                'historia' => 'Parte de la tradición oaxaqueña.',
                'precio' => 450.00,
                'categoria' => $calzadosCat,
                'ubicacion' => $oaxaca,
                'variant_name' => 'Huarache Talla 25',
                'sku' => Str::upper(Str::random(8)),
                'stock' => 15,
                'imagen_variant' => 'images/artesanias/variantes/huarache_25_principal.jpg',
                'tipo_embalaje' => $cajaChica,
                'atributos' => [
                    ['atributo' => $tallaAttr, 'valor' => '25'],
                    ['atributo' => $colorAttr, 'valor' => 'Café/Rojo'],
                    ['atributo' => $materialAttr, 'valor' => 'Cuero/Hilo de algodón'],
                ]
            ]
        ];

        foreach ($productos as $prod) {
            $artesania = Artesania::firstOrCreate(
                ['nombre' => $prod['nombre']],
                [
                    'slug' => Str::slug($prod['nombre']),
                    'descripcion' => $prod['descripcion'],
                    'imagen_artesanias' => $prod['imagen_general'],
                    'historia_piezas_general' => $prod['historia'],
                    'precio' => $prod['precio'],
                    'categoria_id' => $prod['categoria']->id,
                    'ubicacion_id' => $prod['ubicacion']->id,
                ]
            );

            $variant = ArtesaniaVariant::firstOrCreate(
                ['artesania_id' => $artesania->id, 'variant_name' => $prod['variant_name']],
                [
                    'sku' => $prod['sku'],
                    'precio' => $prod['precio'],
                    'stock' => $prod['stock'],
                    'imagen_variant' => $prod['imagen_variant'],
                    'tipo_embalaje_id' => $prod['tipo_embalaje']->id,
                    'is_active' => true,
                ]
            );

            // Insertar atributos
            foreach ($prod['atributos'] as $attr) {
                AtributoArtesaniaVariant::firstOrCreate([
                    'artesania_variant_id' => $variant->id,
                    'atributo_id' => $attr['atributo']->id,
                    'valor' => $attr['valor'],
                ]);
            }
        }
    }
}
