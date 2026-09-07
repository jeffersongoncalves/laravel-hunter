<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hunter.io API Key
    |--------------------------------------------------------------------------
    |
    | Your Hunter.io API key, sent as the `api_key` query parameter on every
    | request. Find yours at https://hunter.io/api-keys
    |
    */
    'api_key' => env('HUNTER_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The Hunter.io v2 REST API base URL. Override only if Hunter.io gives
    | you a dedicated endpoint.
    |
    */
    'base_url' => env('HUNTER_BASE_URL', 'https://api.hunter.io/v2'),
];
