<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Duitku Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Duitku credentials. These values are pulled
    | from your .env file, which should not be committed to source control.
    |
    */

    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key' => env('DUITKU_API_KEY'),
    'api_url' => env('DUITKU_API_URL', 'https://passport.duitku.com/webapi'), // Sandbox URL
    'callback_url' => env('DUITKU_CALLBACK_URL', '/api/callback/duitku'),
];