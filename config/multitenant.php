<?php

return [
/*
  |-----------------------------------------------------------------------
  | MultiTenant Defaults
  |-----------------------------------------------------------------------
  */

    'defaults' =>[
        'driver' =>'web',
        'database' =>env('MASTER_DB',env('DB_CONNECTION')),
    ],

    'drivers' =>[
        'web'=>[
            'driver' => 'session',
            'provider' => 'db-tenants',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'db-tenants',
        ],

        'authcode' =>[
            'driver' =>'authcode',
            'provider' => 'db-tenants',
        ]
    ],

    'providers' =>[
        'db-tenants' =>[
            'driver' =>'eloquent',
            'model' => \Tools4Schools\MultiTenant\Models\Tenant::class
        ],
        'api-tenants' =>[
            'driver' =>'api',
        ]
    ]
];