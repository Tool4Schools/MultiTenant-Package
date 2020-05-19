<?php


namespace Tools4Schools\MultiTenant\Console\Commands;


use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Tools4Schools\MultiTenant\Database\DatabaseManager;
use Tools4Schools\MultiTenant\Traits\Console\AcceptsMultipleTenants;
use Tools4Schools\MultiTenant\Traits\Console\FetchesTenants;

class Seed extends SeedCommand
{
    use FetchesTenants, AcceptsMultipleTenants;

    protected $dbm;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds tenant databases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Resolver $resolver, DatabaseManager $dbm)
    {
        parent::__construct($resolver);
        $this->setName('tenants:seed');

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
            $this->info('Seeding Tenant: '.$tenant->name);
            $this->dbm->createConnection($tenant);
            $this->dbm->connectToTenant();

            parent::handle();

            $this->dbm->purge();
        });
    }
}