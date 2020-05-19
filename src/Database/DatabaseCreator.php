<?php


namespace Tools4Schools\MultiTenant\Database;


use Tools4Schools\MultiTenant\Contracts\Tenant;

class DatabaseCreator extends DatabaseManager
{
    public function getTenantConnection(Tenant $tenant)
    {
        return array_merge(
            config($this->getConfigConnectionPath()),$tenant->tenantConnection->only(['host','port'])
        );
    }
}