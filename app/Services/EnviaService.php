<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Importar la fachada Log

class EnviaService
{
    protected $baseUrl;
    protected $apiToken;
    protected $queriesUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.envia.base_url');
        $this->apiToken = config('services.envia.api_token');
        $this->queriesUrl = config('services.envia.queries_url');

        if (!$this->baseUrl || !$this->apiToken || !$this->queriesUrl) {
            throw new \Exception("Envia API credentials not configured in .env or services.php");
        }
    }

    /**
     * Cotiza opciones de envío con Envia.
     *
     * @param array $payload Los datos de origen, destino y paquetes siguiendo la estructura de Envia.
     * @return array|null Las opciones de envío o null en caso de error.
     */
    public function getShippingQuotes(array $payload): ?array
    {
        try {
            // El endpoint de cotización según la documentación es /ship/rate/
            $endpoint = '/ship/rate/';

            $response = Http::withToken($this->apiToken)
                            ->timeout(30) // Opcional: define un tiempo de espera para la respuesta
                            ->baseUrl($this->baseUrl)
                            ->post($endpoint, $payload); // Enviar el payload completo

            // Verificar si la respuesta fue exitosa (código 2xx)
            if ($response->successful()) {
                // La documentación muestra que el body completo es la respuesta,
                // no necesariamente bajo una clave 'data'. Ajustamos esto.
                return $response->json();
            } else {
                Log::error('Envia API Error (Quotes):', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload,
                    'endpoint' => $this->baseUrl . $endpoint // Para depuración
                ]);
                return null;
            }
        } catch (\Throwable $e) {
            Log::error('Exception calling Envia API (Quotes):', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload // Incluye el payload para depuración
            ]);
            return null;
        }
    }

    // ... otros métodos como createShippingLabel, trackPackage (que ya tienes)
}