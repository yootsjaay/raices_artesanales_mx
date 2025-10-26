<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoEmbalaje;
use App\Services\EnviaService;

class TipoEmbalajeSeeder extends Seeder
{
    public function run(): void
    {
        $envia = new EnviaService();

        $embalajes = [
            // Sobres para ropa individual (camisas, blusas, vestidos, guayaberas)
           [
                "name" => "Sobre pequeño",
                "content" => "Prendas individuales: camisas, blusas, guayaberas",
                "insurance" => 10,
                "height" => 2,
                "length" => 30,
                "width" => 25,
                "weight" => 0.3,
                "package_type_id" => 1, // Sobre
                "weight_unit" => "KG",
                "length_unit" => "CM"
            ],
            [
                "name" => "Caja chica",
                "content" => "Alebrijes chicos, accesorios artesanales",
                "insurance" => 20,
                "height" => 10,
                "length" => 15,
                "width" => 15,
                "weight" => 0.5,
                "package_type_id" => 1,
                "weight_unit" => "KG",
                "length_unit" => "CM"
            ],
            [
                "name" => "Caja mediana",
                "content" => "Textiles, blusas y artesanías medianas",
                "insurance" => 50,
                "height" => 15,
                "length" => 30,
                "width" => 25,
                "weight" => 2,
                "package_type_id" => 1,
                "weight_unit" => "KG",
                "length_unit" => "CM"
            ],
            [
                "name" => "Caja grande",
                "content" => "Surtido de huaraches y calzado",
                "insurance" => 100,
                "height" => 30,
                "length" => 40,
                "width" => 30,
                "weight" => 6,
                "package_type_id" => 1,
                "weight_unit" => "KG",
                "length_unit" => "CM"
            ],
            [
                "name" => "Caja extra",
                "content" => "Piezas de barro grandes: ollas, cántaros, platos",
                "insurance" => 200,
                "height" => 40,
                "length" => 60,
                "width" => 40,
                "weight" => 12,
                "package_type_id" => 1,
                "weight_unit" => "KG",
                "length_unit" => "CM"
            ]
        ];

        foreach ($embalajes as $item) {
            $response = $envia->createPackage($item);

            if ($response && isset($response['package_id'])) {
                TipoEmbalaje::updateOrCreate(
                    ['nombre' => $item['name']],
                    [
                        'descripcion' => $item['content'],
                        'weight' => $item['weight'],
                        'length' => $item['length'],
                        'width' => $item['width'],
                        'height' => $item['height'],
                        'is_active' => 1,
                        'package_envia_id' => $response['package_id'],
                    ]
                );
                $this->command->info("Embalaje '{$item['name']}' creado correctamente con ID {$response['package_id']}");
            } else {
                $this->command->error("Error creando {$item['name']} en Envia.com. Revisa el log para detalles.");
            }
        }
    }
}
