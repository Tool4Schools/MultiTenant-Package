<?php


namespace Tools4Schools\MultiTenant\Traits;


use Illuminate\Support\Facades\Log;

trait BelongsToSystem
{
    public function getConnectionName()
    {
        $connection =  config('multitenant.defaults.database');

        Log::debug('Connection '.$connection);

        return $connection;
    }
}