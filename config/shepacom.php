<?php
return array(
    'default' => 'sandbox',
    'drivers' => [
        'sandbox'  => [
            'api_key'  => 'sandbox',
            'callback' => env('SANDBOX_SHEPA_CALLBACK', 'http://localhost:8000/api/shepa/sandbox/verify'),
        ],
        'merchant' => [
            'api_key'  => env('SHEPACOM_API_KEY',"xxxxxx"),
            'callback' => env('SHEPACOM_CALLBACK', 'http://localhost:8000/api/shepa/merchant/verify'),
        ],
    ],
    'map'     => [
        'sandbox'  => \NasrinRezaei45\Shepacom\Drivers\Sandbox\Sandbox::class,
        'merchant' => \NasrinRezaei45\Shepacom\Drivers\Merchant\Merchant::class,
    ],
);
