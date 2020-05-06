<?php


namespace Tools4Schools\MultiTenant\Console\Commands;


use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\Migrator;

class Migrate extends MigrateCommand
{

    protected  $description = 'Run migrations for tenants';

    public function __construct(Migrator $migrator)
    {
        parent::__construct($migrator);
        $this->setName('tenants:migrate');
    }
}