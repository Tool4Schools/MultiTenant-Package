<?php


namespace Tools4Schools\MultiTenant\Listeners;


use Tools4Schools\MultiTenant\Database\DatabaseManager;
use Tools4Schools\MultiTenant\Events\TenantSwitched;

class ChangeTenantDatabase
{
    protected $dbm;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->dbm = $databaseManager;
    }

    /**
     * Handle the event.
     *
     * @param  \Tools4Schools\MultiTenant\Events\TenantSwitched $event
     * @return void
     */
    public function handle(TenantSwitched $event)
    {
        $this->dbm->createConnection($event->tenant);
        $this->dbm->connectToTenant();
    }
}