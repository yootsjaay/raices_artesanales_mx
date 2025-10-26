<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Importar la clase Log

class EnviaService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
       $this->apiKey = config('services.envia.api_key');
        $this->baseUrl = config('services.envia.base_url');
        $this->baseQueries= config('services.envia.base_queries');

    }

    /**
     * Realiza una cotización de envío con la estructura de datos de Envía.com.
     *
     * @param array $data Los datos para la cotización (origin, destination, packages, shipment, settings)
     * @return array|null
     */
    public function quoteShipping(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post("{$this->baseUrl}/ship/rate/", $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Error al cotizar envío con Envia.com:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'request_data' => $data // Opcional: para depuración, puedes loggear los datos enviados
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Excepción al cotizar envío con Envia.com: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
/**
 * Crea una guía de envío.
 *
 * @param array $data Los datos para crear la guía
 * @return array|null
 */
public function createShipment(array $data)
{
    try {
        // Log para verificar el payload antes de enviarlo
        Log::debug('Payload enviado a Envia.com:', $data);

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseUrl}/ship/generate/", $data);

        if ($response->successful()) {
            Log::info('Etiqueta generada correctamente desde Envia.com');
            return $response->json(); // Devuelve el contenido JSON decodificado
        } else {
            Log::error('Error al generar la guía con Envia.com:', [
                'status'       => $response->status(),
                'error_body'   => $response->json(),  // o usa ->body() si el JSON falla
                'request_data' => $data,
            ]);
            return [
                'status' => $response->status(),
                'error'  => $response->json(), // Mejor para ver desde consola
            ];
        }
    } catch (\Exception $e) {
        Log::critical('Excepción al crear la guía con Envia.com: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        return [
            'error' => 'Exception',
            'message' => $e->getMessage()
        ];
    }
}
    public function createPackage(array $data){
    try {
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post("{$this->baseQueries}/packages", $data);

        if ($response->successful()) {
            Log::info('Paquete creado exitosamente', [
                'response' => $response->json()
            ]);
            return $response->json();
        } else {
            Log::error('Error al crear paquete en Envia.com', [
                'status'       => $response->status(),
                'body'         => $response->body(),
                'request_data' => $data,
            ]);
            return null;
        }
    } catch (\Exception $e) {
        Log::error('Exception al crear paquete en Envia.com: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return null;
    }
}


    // Puedes agregar más métodos según las funcionalidades que necesites, siguiendo la misma estructura.
}