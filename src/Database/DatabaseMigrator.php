<?php


namespace Tools4Schools\MultiTenant\Database;


use Tools4Schools\MultiTenant\Contracts\Tenant;

class DatabaseMigrator extends DatabaseManager
{
    public function getTenantConnection(Tenant $tenant)
    {
        return array_merge(
            config($this->getConfigConnectionPath()),$tenant->tenantConnection->only(['database','host','port'])
        );
    }
}