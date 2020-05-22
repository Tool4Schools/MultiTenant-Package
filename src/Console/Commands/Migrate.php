<?php


namespace Tools4Schools\MultiTenant\Console\Commands;



use Illuminate\Database\Migrations\Migrator;
use Tools4Schools\MultiTenant\Database\DatabaseMigrator;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Tools4Schools\MultiTenant\Traits\Console\FetchesTenants;
use Tools4Schools\MultiTenant\Traits\Console\AcceptsMultipleTenants;

class Migrate extends MigrateCommand
{
    use FetchesTenants, AcceptsMultipleTenants;

    protected $dbm;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'tenants:migrate {tenant?}';

    protected  $description = 'Run migrations for tenants';

    public function __construct(Migrator $migrator, DatabaseMigrator $dbm)
    {
        parent::__construct($migrator);
        $this->setName('tenants:migrate');

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
            $this->info('Migrating Tenant: '.$tenant->name);
            $this->dbm->createConnection($tenant);
            $this->dbm->connectToTenant();

            parent::handle();

            $this->dbm->purge();
        });
    }

    protected function getMigrationPaths()
    {
        return [database_path('migrations')];
    }
}