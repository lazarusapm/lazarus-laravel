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

    'ips' => true,
];
