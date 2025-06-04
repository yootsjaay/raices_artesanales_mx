<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Validation\ValidationException; // Para manejar errores de validación de Envia.com

class EnviaService
{
    protected $apiKey;
    protected $baseUrl;
    protected $queriesUrl; // Aunque no lo usaremos para cotizar directamente aquí, es bueno tenerlo

    public function __construct()
    {
        $this->apiKey = config('services.envia.api_token');
        $this->baseUrl = config('services.envia.base_url');
        $this->queriesUrl = config('services.envia.queries_url'); // No se usa en este método, pero está disponible

        if (empty($this->apiKey)) {
            throw new Exception('ENVIA_API_TOKEN no está configurada en config/services.php o .env');
        }
        if (empty($this->baseUrl)) {
            throw new Exception('ENVIA_BASE_URL no está configurada en config/services.php o .env');
        }
    }

    /**
     * Obtiene una cotización de envío de la API de Envia.com.
     * Utiliza el endpoint /shipments/quotes que espera un payload completo.
     *
     * @param array $data Los datos estructurados de origen, destino y paquetes.
     * @return array|null La respuesta de la API (cotizaciones) o null si hay un error.
     * @throws ValidationException Si la API de Envia.com devuelve errores de validación.
     */
    public function getQuote(array $data): ?array
    {
        // El endpoint para cotizaciones (confirma con la documentación de Envia.com)
        $endpoint = $this->baseUrl . '/ship/rate'; // Este es el endpoint más común para cotizaciones completas

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $data); // Pasamos $data directamente, ya que se espera que esté bien estructurada

            if ($response->successful()) {
                return $response->json();
            } elseif ($response->clientError()) { // 4xx errors
                // Envia.com a veces devuelve errores de validación con status 400
                $errorMessage = 'Error de cliente al cotizar con Envia.com. ';
                $responseJson = $response->json();

                // Si la respuesta tiene errores de validación de la API
                if (isset($responseJson['errors']) && is_array($responseJson['errors'])) {
                    $errors = [];
                    foreach ($responseJson['errors'] as $field => $messages) {
                        $errors[$field] = is_array($messages) ? implode(', ', $messages) : $messages;
                    }
                    // Puedes lanzar una excepción de validación para que Laravel la capture
                    throw ValidationException::withMessages($errors);
                } else {
                    $errorMessage .= 'Código: ' . $response->status() . ' - ' . ($responseJson['message'] ?? 'Error desconocido.');
                    \Log::error($errorMessage, [
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                        'request_payload' => $data,
                        'endpoint' => $endpoint
                    ]);
                }
                return null;
            } else { // 5xx errors or other unexpected status
                \Log::error('Error del servidor al cotizar con Envia.com:', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                    'request_payload' => $data,
                    'endpoint' => $endpoint
                ]);
                return null;
            }
        } catch (Exception $e) {
            \Log::error('Excepción al conectar con la API de Envia.com:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_payload' => $data,
                'endpoint' => $endpoint
            ]);
            return null;
        }
    }

    // Puedes añadir otros métodos aquí como createShipment, trackShipment, etc.
}