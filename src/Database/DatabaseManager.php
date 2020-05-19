<?php


namespace Tools4Schools\MultiTenant\Database;

use Illuminate\Database\DatabaseManager as LaravelDatabaseManager;
use Tools4Schools\MultiTenant\Contracts\Tenant;

class DatabaseManager
{
    protected $dbm;

    public function __construct(LaravelDatabaseManager $databaseManager)
    {
        $this->dbm = $databaseManager;
    }

    public function createConnection(Tenant $tenant)
    {
        config()->set('database.connections.tenant',$this->getTenantConnection($tenant));
    }

    public function connectToTenant()
    {
        $this->dbm->setDefaultConnection('tenant');
        $this->dbm->reconnect('tenant');
    }

    public function purge()
    {
        $this->dbm->purge('tenant');
    }

    public function getDefaultConnectionName()
    {
        return config('database.default');
    }

    protected function getTenantConnection(Tenant $tenant)
    {
        return array_merge(
            config($this->getConfigConnectionPath()),$tenant->tenantConnection->only(['database','host','port','username','password'])
        );
    }

    protected function getConfigConnectionPath()
    {
        return sprintf('database.connections.%s', $this->getDefaultConnectionName());
    }
}