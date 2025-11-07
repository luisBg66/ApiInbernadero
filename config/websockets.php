<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WebSockets Apps
    |--------------------------------------------------------------------------
    |
    | Define the applications that are allowed to connect to the websocket
    | server. The `id`, `key` and `secret` values come from your .env and
    | should match the values used by your client (Laravel Echo / Pusher).
    |
    */

    'apps' => [
        [
            'id' => env('PUSHER_APP_ID', 'local'),
            'name' => env('APP_NAME', 'Laravel'),
            'key' => env('PUSHER_APP_KEY', 'local'),
            'secret' => env('PUSHER_APP_SECRET', 'secret'),
            'path' => env('PUSHER_APP_PATH', ''),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],

    'dashboard' => [
        'port' => env('WEBSOCKETS_DASHBOARD_PORT', 6001),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum request size (bytes)
    |--------------------------------------------------------------------------
    */
    'max_request_size' => 250000, // ~250 KB

    /*
    |--------------------------------------------------------------------------
    | Path for the statistics (if enabled)
    |--------------------------------------------------------------------------
    */
    'statistics' => [
        'model' => null,
    ],

];
