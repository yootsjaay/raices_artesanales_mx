<?php
namespace App\Services; // <--- ¡Asegúrate de que este sea el correcto!
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // Importar la fachada Cache

class SkydropxService {
    protected $client;
    protected $apiKey;
    protected $baseUrl;
    protected $clientId;
    protected $clienteSecret;
    protected $oauthUrl;
    
    public function __construct(){
        $this->apiKey=config('services.skydropx.api_token');
        $this->baseUrl= config('services.skydropx.base_url');
        $this->clientId = config('services.skydropx.client_id');
        $this->clientSecret = config('services.skydropx.client_secret');
        $this->oauthUrl = config('services.skydropx.oauth_url');
        // El cliente principal de la API se inicializa con el token obtenido

        $this->cliente= new Cliente([
            'base_uri'=>$this->baseUrl,
            'verify' => config('services.skydropx.ssl_verify', true),
        ]);
    }

    /**generacion de token dinamico cada 2 horas  */
    public function getAccessToken():string{
        //1.intentar obtener el token del cache 
        $accessToken=Cache::get('skydropx_access_token');

        if(!$accessToken){
            //2.si no esta el token en cache o ha expirado generar uno nuevo 
            Long::info('Skydropx: Token no encontrado o expirado, solicita uno nuevo ');
            try {
            $oauthClient= new Client([
                'base_uri' =>$this->oauthUrl,
                'verify' =>config('services.skydropx.ssl_verify', true),

            ]);
            $response= $oauthClient->post('',[
                'headers' => [
                    'Content-Type' =>'aplication/x-www-form-urlencoded',
                    'Accepted' =>'application/json',
                ],
                'form_params' => [
                    'grant_type'=> 'client_credentials',
                    'client_id' => $this->clienId,
                    'client_secret' => $this->clienSecret,
                ],


            ]);
            $data = json_encode($response->getBody()->getContents(),true);
            if(!isset($data['access_token'])|| !isset($data['expires_in'])){
                throw new \Exception('Respuesta de token inesperada de Skydropx');

            }

            $accessToken= $data['access_token'];
            //la documentacion indica que expira en 2 horas 
            //Cacheamos un poco menos para evitar usar un token casi expirado

            $expiresInSeconds = $data['expires_in']?? 7200;
            $cacheDuration = ($expiresInSeconds > 60)? $expiresInSeconds - 60 : $expiresInSeconds;//cachear por 2 horas 

            Cache::put('skydropx_access_token', $accessToken, now()->addSeconds($cacheDuration));
            Log::info('SkydropX: Nuevo Token obtenido y cacheado con exito');
            }catch(ClientException $e){
                $responseBody = $e->getResponse()->getBody(true);
                $errorMessage = json_encode($responseBody, true) ? : ['message'=>$e->gerMessage()];
                 Log::error('SkydropX Client Error (getAccessToken): ' . $e->getMessage() . ' - Response: ' . $responseBody);
                throw new \Exception('Error al obtener el token de acceso de SkydropX: ' . json_encode($errorMessage));
            } catch (\Exception $e) {
                Log::error('SkydropX Error (getAccessToken): ' . $e->getMessage());
                throw new \Exception('Error inesperado al obtener el token de acceso de SkydropX: ' . $e->getMessage());
            }
     }

        return $accessToken;
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
     * @return array|null Retorna la respuesta de la API o un array con error.
     */
    public function createQuotation(
        array $addressFrom,
        array $addressTo,
        array $parcel,
        ?string $orderId = null,
        array $requestedCarriers = []
    ): ?array {
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

        try {
            // Esto es equivalente a la petición cURL que mostraste
            $response = $this->client->post('quotations', [
                'json' => $data, // Guzzle serializa $data a JSON y establece Content-Type: application/json
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result;

        } catch (ClientException $e) {
            $responseBody = $e->getResponse()->getBody(true);
            $errorMessage = json_decode($responseBody, true) ?: $e->getMessage();
            Log::error('SkydropX Client Error (createQuotation): ' . $e->getMessage() . ' - Response: ' . $responseBody);
            return ['error' => $errorMessage, 'status_code' => $e->getResponse()->getStatusCode()];
        } catch (\Exception $e) {
            Log::error('SkydropX Error (createQuotation): ' . $e->getMessage());
            return ['error' => 'Error al crear la cotización.', 'message' => $e->getMessage()];
        }
    }



}