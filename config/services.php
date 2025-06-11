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
// config/services.php
'skydropx' => [
    'client_id' => env('SKYDROPX_CLIENT_ID'),
    'client_secret' => env('SKYDROPX_CLIENT_SECRET'),
    'base_url' => env('SKYDROPX_BASE_URL'),   // <--- NO second parameter here!
    'oauth_url' => env('SKYDROPX_OAUTH_URL'), // <--- NO second parameter here!
    'ssl_verify' => env('SKYDROPX_SSL_VERIFY', true),
],
    

];
