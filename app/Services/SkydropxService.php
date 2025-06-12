<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class SkydropxService
{
    protected Client $client;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.skydropx.client_id');
        $this->clientSecret = config('services.skydropx.client_secret');

        // El base_uri para el cliente Guzzle principal se puede mantener genérico o se podría omitir si todas las peticiones son con URL completa.
        // apuntando al dominio principal sin un path específico.
        $this->client = new Client([
            'base_uri' => 'https://sb-pro.skydropx.com/',
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
        $oauthUrl = 'https://sb-pro.skydropx.com/api/v1/oauth/token'; // URL de OAuth directamente 
        $accessToken = Cache::get('skydropx_access_token');

        if (!$accessToken) {
            Log::info('SkydropX: Token no encontrado o expirado, solicitando nuevo token.');
            try {
                $oauthClient = new Client([
                    'base_uri' => $oauthUrl,
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
                        'scope' => 'default orders.create',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (!isset($data['access_token']) || !isset($data['expires_in'])) {
                    throw new \Exception('Respuesta de token inesperada de SkydropX: ' . json_encode($data));
                }

                $accessToken = $data['access_token'];
                $expiresInSeconds = $data['expires_in'] ?? 7200;
                $cacheDuration = ($expiresInSeconds > 60) ? $expiresInSeconds - 60 : $expiresInSeconds;

                Cache::put('skydropx_access_token', $accessToken, now()->addSeconds($cacheDuration));
                Log::info('SkydropX: Nuevo token obtenido y cacheado con éxito.');

            } catch (ClientException $e) {
                $statusCode = $e->getResponse()->getStatusCode();
                $responseBody = (string) $e->getResponse()->getBody();
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
        $accessToken = $this->getAccessToken();

        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        try {
            // Se usa la URI completa en la llamada request para asegurar que no dependa del base_uri del cliente
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $errorMessage = json_decode($responseBody, true) ?: ['message' => $e->getMessage()];
            Log::error("SkydropX Client Error ({$method} {$uri}): " . $e->getMessage() . ' - Response: ' . $responseBody);
            throw $e;
        } catch (\Exception $e) {
            Log::error("SkydropX Error ({$method} {$uri}): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crea una orden con SkydropX.
     * Endpoint: POST /api/v1/orders
     *
     * @param array $orderData Datos de la orden a crear.
     * @return array Retorna la respuesta de la API.
     * @throws \Exception Si ocurre un error al crear la orden.
     */
    public function createOrder(array $orderData): array
    {
        try {
            $token = $this->getAccessToken();
            $ordersUrl = 'https://sb-pro.skydropx.com/api/v1/orders'; // URL de órdenes directamente aquí

            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(10)
                ->post($ordersUrl, $orderData);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();
                Log::error('Error al crear orden SkydropX', [
                    'status' => $status,
                    'body' => $body,
                    'orderData' => $orderData,
                ]);
                $message = "Error al crear orden. HTTP status: $status. Response: " . ($body ?: 'Sin contenido');
                throw new \Exception($message);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Excepción al crear orden SkydropX', [
                'message' => $e->getMessage(),
                'orderData' => $orderData,
            ]);
            throw $e;
        }
    }

    /**
     * Introspecta un token de SkydropX.
     * Endpoint: POST /api/v1/oauth/introspect
     *
     * @param string $token El token a introspectar.
     * @return array Retorna la respuesta de la API de introspección.
     * @throws \Exception Si ocurre un error al introspectar el token.
     */
    public function introspectToken(string $token): array
    {
        $introspectUrl = 'https://sb-pro.skydropx.com/api/v1/oauth/introspect'; // URL de introspección directamente aquí
        $clientId = config('services.skydropx.client_id');
        $clientSecret = config('services.skydropx.client_secret');

        $response = Http::asForm()->post($introspectUrl, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'token' => $token,
            'token_type_hint' => 'access_token',
        ]);

        if (!$response->successful()) {
            Log::error('Error introspectando token SkydropX', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Error al introspectar token: HTTP {$response->status()}");
        }

        return $response->json();
    }

    /**
     * Crea una cotización con SkydropX.
     * Endpoint: POST /api/v1/quotations
     *
     * @param array $data Datos para la cotización (order_id, address_from, address_to, parcel, requested_carriers).
     * @return array Retorna la respuesta de la API.
     * @throws ClientException|\Exception Si ocurre un error al crear la cotización.
     */
    public function cotizar(array $data): array
    {    $orderId = $data['quotation']['order_id']; // ← acceso correcto

        $quotationsUrl = "https://sb-pro.skydropx.com/api/v1/quotations/{$orderId}";

        $body = [
            'quotation' => [
                'order_id' => $data['order_id'],
                'address_from' => $data['address_from'],
                'address_to' => $data['address_to'],
                'parcel' => $data['parcel'],
                'requested_carriers' => $data['requested_carriers'] ?? [],
            ],
        ];

        // Se pasa la URL completa al método request
        return $this->request('GET', $quotationsUrl, [
            'json' => $body,
        ]);
    
}

}