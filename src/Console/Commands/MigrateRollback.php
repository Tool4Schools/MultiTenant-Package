<?php


namespace Tools4Schools\MultiTenant\Console\Commands;

use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Migrations\Migrator;
use Tools4Schools\MultiTenant\Database\DatabaseMigrator;
use Tools4Schools\MultiTenant\Traits\Console\AcceptsMultipleTenants;
use Tools4Schools\MultiTenant\Traits\Console\FetchesTenants;

class MigrateRollback extends RollbackCommand
{
    use FetchesTenants,AcceptsMultipleTenants;

    protected $dbm;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback migrations for tenants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Migrator $migrator, DatabaseMigrator $dbm)
    {
        parent::__construct($migrator);
        $this->setName('tenants:rollback');

        $this->specifyParameters();

        $this->dbm = $dbm;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->input->setOption('database', 'tenant');

        $this->tenants($this->option('tenants'))->each(function ($tenant) {
            $this->info('Rolling back Migrations for Tenant: '.$tenant->name);
            $this->dbm->createConnection($tenant);
            $this->dbm->connectToTenant();

            parent::handle();

            $this->dbm->purge();
        });
    }

    protected function getMigrationPaths()
    {
        return [database_path('migrations/tenant')];
    }
}