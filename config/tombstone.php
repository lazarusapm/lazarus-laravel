<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Tombstone
    |--------------------------------------------------------------------------
    |
    | Here you may specify if Tombstone should be enabled.
    |
    */

    'enabled' => env('TOMBSTONE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | Here you must set your unique Tombstone project token.
    |
    */

    'token' => env('TOMBSTONE_TOKEN'),

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
