<?php


namespace Tools4Schools\MultiTenant\Contracts;


interface TenantProvider
{
    /**
     * Retrieve a tenant by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return Tools4Schools\MultiTenant\Contracts\Tenant|null
     */
    public function retrieveById($identifier):Tenant;


}