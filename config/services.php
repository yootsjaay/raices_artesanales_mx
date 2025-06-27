<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'mercadopago'=>[
        'public_key'=> 'MP_ACCESS_TOKEN'
    ],
'envia' => [
    'base_url' => env('ENVIA_BASE_URL'),
    'api_key' => env('ENVIA_API_KEY'),
    'origin' => env('ENVIA_ORIGIN', true),
    'origin_address_id' => env('ENVIA_ORIGIN_ADDRESS_ID'),

    'origin_details' => [
        "number" => "104", // **Tu número exterior**
        "postalCode" => "68000", // **Tu código postal**
        "type" => "origin",
        "company" => "Raices Artesanas", // El nombre de tu negocio
        "name" => "Bralio Cardozo Vasquez", // Tu nombre o el de la persona de contacto
        "email" => "raices@artesanales.mx", // Tu email de contacto
        "phone" => "9514537503", // Tu teléfono de contacto
        "country" => "MX",
        "street" => "Humboldt", // El nombre de tu calle
        "district" => "Colonia Centro", // Tu colonia o barrio
        "city" => "Oaxaca", // Tu ciudad
        "state" => "Oaxaca de Juarez", // Tu estado (ej. "OAX" para Oaxaca)
        "phone_code" => "MX",
        "category" => 1,
        "identificationNumber" => "N/A", // Si aplica, un RFC, CURP o identificación
        "reference" => "Referencias adicionales para encontrar tu taller o local"
    ],

],

    

];
