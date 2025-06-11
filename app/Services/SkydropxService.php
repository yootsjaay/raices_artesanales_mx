<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SkydropxService
{
    protected Client $client; // Corregido: tipado para GuzzleHttp\Client
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret; // Corregido: nombre de propiedad consistente
    protected string $oauthUrl;

    public function __construct()
    {
        // Eliminada: $this->apiKey=config('services.skydropx.api_token'); (obsoleto con OAuth)
        $this->baseUrl = config('services.skydropx.base_url');
        $this->clientId = config('services.skydropx.client_id');
        $this->clientSecret = config('services.skydropx.client_secret'); // Corregido: asignación a la propiedad correcta
        $this->oauthUrl = config('services.skydropx.oauth_url');

        // Corregido: $this->client en lugar de $this->cliente
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'verify' => config('services.skydropx.ssl_verify', true),
        ]);
    }

    /**
     * Generación de token dinámico cada 2 horas
     *
     * @return string El token de acceso Bearer.
     * @throws \Exception Si no se puede obtener el token de acceso.
     */
    protected function getAccessToken(): string
    {
        $accessToken = Cache::get('skydropx_access_token');

        if (!$accessToken) {
            Log::info('SkydropX: Token no encontrado o expirado, solicitando nuevo token.');
            try {
                $oauthClient = new Client([
                    'base_uri' => $this->oauthUrl,
                    'verify' => config('services.skydropx.ssl_verify', true),
                ]);

                $response = $oauthClient->post('', [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'client_credentials',

                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (!isset($data['access_token']) || !isset($data['expires_in'])) {
                    throw new \Exception('Respuesta de token inesperada de SkydropX: ' . json_encode($data));
                }

                $accessToken = $data['access_token'];
                $expiresInSeconds = $data['expires_in'] ?? 7200;
                $cacheDuration = ($expiresInSeconds > 60) ? $expiresInSeconds - 60 : $expiresInSeconds; // Cachear por 2 horas menos 1 minuto

                Cache::put('skydropx_access_token', $accessToken, now()->addSeconds($cacheDuration));
                Log::info('SkydropX: Nuevo token obtenido y cacheado con éxito.');

            } catch (ClientException $e) {
                $statusCode = $e->getResponse()->getStatusCode(); // Obtener el código de estado
                $responseBody = (string) $e->getResponse()->getBody(); // Obtener el cuerpo de la respuesta RAW
                // Corregido: $e->getMessage() en lugar de $e->gerMessage()
                $errorMessage = json_decode($responseBody, true) ?: ['message' => $e->getMessage()];

                Log::error(
                    'SkydropX Client Error (getAccessToken): ',
                    [
                        'status_code' => $statusCode,
                        'message' => $e->getMessage(),
                        'response_body_raw' => $responseBody,
                        'parsed_error_message' => $errorMessage,
                    ]
                );
                // Mejorado: Mensaje de excepción más informativo
                throw new \Exception(
                    'Error al obtener el token de acceso de SkydropX. Status: ' . $statusCode .
                    '. Detalles: ' . json_encode($errorMessage)
                );
            } catch (\Exception $e) {
                Log::error('SkydropX Error (getAccessToken): ' . $e->getMessage());
                throw new \Exception('Error inesperado al obtener el token de acceso de SkydropX: ' . $e->getMessage());
            }
        }

        return $accessToken;
    }

    /**
     * Realiza una petición a la API de SkydropX.
     * Este método es una envoltura para añadir el token de autorización automáticamente.
     *
     * @param string $method El método HTTP (GET, POST, etc.).
     * @param string $uri La URI relativa al base_uri.
     * @param array $options Opciones de Guzzle (json, form_params, query, etc.).
     * @return array La respuesta decodificada de la API.
     * @throws ClientException|\Exception Si ocurre un error en la petición.
     */
    protected function request(string $method, string $uri, array $options = []): array
    {
        $accessToken = $this->getAccessToken(); // Obtener el token (o refrescarlo si es necesario)

        // Añadir el encabezado de autorización a la petición
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json', // Por defecto para la API principal
            'Accept' => 'application/json',
        ]);

        try {
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $responseBody = $e->getResponse()->getBody(true);
            $errorMessage = json_decode($responseBody, true) ?: ['message' => $e->getMessage()];
            Log::error("SkydropX Client Error ({$method} {$uri}): " . $e->getMessage() . ' - Response: ' . $responseBody);
            throw $e;
        } catch (\Exception $e) {
            Log::error("SkydropX Error ({$method} {$uri}): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crea una cotización con SkydropX.
     * Endpoint: POST /api/v1/quotations
     *
     * @param array $addressFrom Datos de la dirección de origen.
     * @param array $addressTo Datos de la dirección de destino.
     * @param array $parcel Datos del paquete (length, width, height, weight).
     * @param string|null $orderId ID de la orden interna para referenciar la cotización.
     * @param array $requestedCarriers Lista de paqueterías a cotizar (opcional).
     * @return array Retorna la respuesta de la API.
     * @throws ClientException|\Exception Si ocurre un error al crear la cotización.
     */
    public function createQuotation(
        array $addressFrom,
        array $addressTo,
        array $parcel,
        ?string $orderId = null,
        array $requestedCarriers = []
    ): array {
        $data = [
            'address_from' => $addressFrom,
            'address_to' => $addressTo,
            'parcel' => $parcel,
        ];

        if ($orderId) {
            $data['order_id'] = $orderId;
        }
        if (!empty($requestedCarriers)) {
            $data['requested_carriers'] = $requestedCarriers;
        }

        return $this->request('POST', 'quotations', [
            'json' => $data,
        ]);
    }
}