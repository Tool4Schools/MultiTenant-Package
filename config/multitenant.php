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
            'provider' => 'api-tenants',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'db-tenants',
        ],
    ],
];