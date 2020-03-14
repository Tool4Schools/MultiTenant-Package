<?php

namespace Tools4Schools\MultiTenant\Facades;

use Illuminate\Support\Facades\Facade;
use Tools4Schools\MultiTenant\Contracts\TenantManager as TenantManagerContract;

/**
 * @method static void loadTenant($uuid)
 * @method static \Tools4Schools\MultiTenant\TenantManager registerDriver(string $driver, \Closure $callback)
 *
 * @see Tools4Schools\MultiTenant\TenantManager
 */

class TenantManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TenantManagerContract::class;
    }
}


