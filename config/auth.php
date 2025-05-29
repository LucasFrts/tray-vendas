<?php
return [

    // …

    'defaults' => [
        'guard' => 'web',      // guard padrão para web
        'passwords' => 'users' // provider de reset padrão
    ],
    'guards' => [
        // Users (web)
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
        // Sellers (web)
        'seller' => [
            'driver'   => 'session',
            'provider' => 'sellers',
        ],
        // API (Sanctum, único guard – suporta ambos os models que usam HasApiTokens)
        'sanctum' => [
            'driver'   => 'sanctum',
            'provider' => 'users',
            // embora o provider aponte pra users, o Sanctum irá autenticar qualquer Model que use HasApiTokens
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
        'sellers' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Seller::class,
        ],
    ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            // ...
        ],
        'sellers' => [
            'provider' => 'sellers',
            // ...
        ],
    ],

];
