<?php


namespace Tools4Schools\MultiTenant\Contracts;


interface TenantDriver
{
    /**
     *
     * @return Tenant|null
     */
    public function tenant():?Tenant;

    /**
     * @param Tenant $tenant
     * @return null
     */
    public function switchTenant(Tenant $tenant);
}