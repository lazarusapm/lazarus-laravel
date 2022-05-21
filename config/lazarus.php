<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Lazarus
    |--------------------------------------------------------------------------
    |
    | Here you may specify if Lazarus should be enabled.
    |
    */

    'enabled' => env('LAZARUS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | Here you must set your unique Lazarus project token.
    |
    */

    'token' => env('LAZARUS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Exclude routes
    |--------------------------------------------------------------------------
    |
    | Here you may define routes that should not be logged, ie. "api.test.index"
    |
    */

    'exclude' => [
        // ... list route names
    ],

    /*
    |--------------------------------------------------------------------------
    | Include IP Address
    |--------------------------------------------------------------------------
    |
    | Here you may specify if visitor IPs should be logged.
    |
    */

    'ips' => env('LAZARUS_LOG_IPS', true),

    /*
    |--------------------------------------------------------------------------
    | Include Device Info
    |--------------------------------------------------------------------------
    |
    | Here you may specify if visitor device information should be logged.
    |
    */

    'devices' => env('LAZARUS_LOG_DEVICES', true),

    /*
    |--------------------------------------------------------------------------
    | Custom Endpoint
    |--------------------------------------------------------------------------
    |
    | Here you may specify a custom endpoint for debugging purposes.
    | *Caution!* Lazarus produces a _lot_ of requests!
    |
    */

    'endpoint' => env('LAZARUS_ENDPOINT'),
];
