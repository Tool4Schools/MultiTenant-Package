<?php

return [
/*
  |-----------------------------------------------------------------------
  | MultiTenant Defaults
  |-----------------------------------------------------------------------
  */

    'defaults' =>[
        'driver' =>'web',
    ],

    'drivers' =>[
        'web'=>[
            'driver' => 'session',
            'provider' => 'db-tenants',
            //'provider' => 'api-tenants',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'tenants',
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