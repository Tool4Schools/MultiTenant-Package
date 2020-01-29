<?php


namespace Tools4Schools\MultiTenant\Drivers;


use Tools4Schools\MultiTenant\Models\Tenant;

abstract class TenantDriver
{

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * Determine if the tenant has been identified
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->tenant());
    }
}