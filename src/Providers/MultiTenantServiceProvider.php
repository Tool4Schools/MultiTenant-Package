<?php

namespace Tools4Schools\MultiTenant\Providers;



use Illuminate\Routing\Router;


use Illuminate\Support\ServiceProvider;

use Tools4Schools\MultiTenant\TenantManager;
use Tools4Schools\MultiTenant\Drivers\TokenDriver;
use Tools4Schools\MultiTenant\Drivers\DomainDriver;
use Tools4Schools\MultiTenant\Drivers\SessionDriver;
use Tools4Schools\MultiTenant\Console\Commands\Seed;
use Tools4Schools\MultiTenant\Drivers\AuthCodeDriver;
use Tools4Schools\MultiTenant\Database\DatabaseManager;
use Tools4Schools\MultiTenant\Console\Commands\Migrate;
use Tools4Schools\MultiTenant\Database\DatabaseMigrator;
use Tools4Schools\MultiTenant\Console\Commands\MigrateRollback;
use Tools4Schools\MultiTenant\Drivers\Providers\EloquentProvider;
use Tools4Schools\MultiTenant\Contracts\TenantManager as TenantManagerContract;
use Tools4Schools\MultiTenant\Facades\TenantManager as TenantManagerFacade;


class MultiTenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->bind(TenantRepositoryContract::class,TenantRepository::class);
        //$this->createTenantManager();

        //  $this->createRequestMacros();

        // $this->createBladeSyntax();

        $this->setupCommands();
        /*if($this->app->runningInConsole()) {
        }
*/
        $this->publishes([
            __DIR__.'/../../config/multitenant.php' => config_path('multitenant.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../../resources/views','tenant');


        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        //$this->app->make(Router::class)->aliasMiddleware('tenant.identify',IdentifyTenant::class);

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../../config/multitenant.php', 'multitenant');
        }
        $this->registerManager();
        $this->registerDrivers();
        $this->registerTenantProviders();
        $this->registerEventRebindHandler();
        //$this->app->alias(TenantManager::class,'multitenant');

        $this->registerCommands();
    }


    protected function registerManager()
    {

        $this->app->singleton(TenantManagerContract::class, function ($app){
            return new TenantManager($app);
        });

        //$this->app->alias(TenantManager::class,Manager::class);
    }

    protected function registerDrivers()
    {
        $this->registerDomainDriver();
        $this->registerSessionDriver();
        $this->registerTokenDriver();
        $this->registerAuthCodeDriver();
    }

    protected function registerDomainDriver()
    {
        TenantManagerFacade::resolved(function ($multitenant){
            $multitenant->registerDriver('domain',function ($app,$name,$provider, array $config){
                return new DomainDriver();
            });
        });
    }

    protected function registerSessionDriver()
    {
        TenantManagerFacade::resolved(function ($multitenant){
            $multitenant->registerDriver('session',function ($app,$name,$provider, array $config){
                return new SessionDriver($name,$provider,$this->app['session.store']);
            });
        });
    }
    protected function registerTokenDriver()
    {
        TenantManagerFacade::resolved(function ($multitenant){
            $multitenant->registerDriver('token',function ($app,$name,$provider, array $config){
                return new TokenDriver($name,$provider,$app['request']);
            });
        });
    }

    protected function registerAuthCodeDriver()
    {
        TenantManagerFacade::resolved(function ($multitenant){
            $multitenant->registerDriver('authcode',function ($app,$name,$provider, array $config){
                return new AuthCodeDriver($name,$provider,$app['request']);
            });
        });
    }

    protected function registerTenantProviders()
    {
        $this->registerEloquentProvider();
    }

    protected function registerEloquentProvider()
    {
        TenantManagerFacade::resolved(function ($multitenant){
            $multitenant->registerProvider('eloquent',function ($app, array $config){
                return new EloquentProvider($config['model']);
            });
        });
    }


    /**
     * Handle the re-binding of the event dispatcher binding.
     *
     * @return void
     */
    protected function registerEventRebindHandler()
    {
        $this->app->rebinding('events', function ($app, $dispatcher) {
            if (! $app->resolved(TenantManagerContract::class)) {
                return;
            }

            if ($app[TenantManagerContract::class]->hasResolvedGuards() === false) {
                return;
            }

            if (method_exists($guard = $app[TenantManagerContract::class]->driver(), 'setDispatcher')) {
                $guard->setDispatcher($dispatcher);
            }
        });
    }



    protected function registerCommands()
    {
        $this->app->singleton(Migrate::class,function ($app){
            return new Migrate($app->make('migrator'),$app->make(DatabaseMigrator::class));
        });

        $this->app->singleton(MigrateRollback::class,function ($app){
            return new MigrateRollback($app->make('migrator'),$app->make(DatabaseMigrator::class));
        });

        $this->app->singleton(Seed::class,function ($app){
            return new Seed($app->make('db'),$app->make(DatabaseManager::class));
        });
    }


    protected function setupCommands()
    {
        $this->commands([
            Migrate::class,
            MigrateRollback::class,
            Seed::class,
        ]);
    }
}
