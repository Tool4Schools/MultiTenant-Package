<?php

namespace Tools4Schools\MultiTenant\Providers;



use Illuminate\Routing\Router;

use Illuminate\Support\ServiceProvider;

use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenant;
use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingDomain;
//use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingSession;
//use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingToken;
use Tools4Schools\MultiTenant\Contracts\TenantManager as TenantManagerContract;
use Tools4Schools\MultiTenant\TenantManager;


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

        //$this->setupCommands();
        /*if($this->app->runningInConsole()) {
        }
*/
        $this->publishes([
            __DIR__.'/../../config/multitenant.php' => config_path('multitenant.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../../resources/views','tenant');

        $this->app->make(Router::class)->aliasMiddleware('tenant.identify',IdentifyTenant::class);
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
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
        //$this->app->alias(TenantManager::class,'multitenant');

        //$this->registerCommands();
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
    }

    protected function registerDomainDriver()
    {
        MultiTenant::resolved(function ($multitenant){
            $multitenant->registerDriver('domain',function ($app,$name, array $config){

            });
        });
    }

    protected function registerSessionDriver()
    {
        MultiTenant::resolved(function ($multitenant){
            $multitenant->registerDriver('session',function ($app,$name, array $config){

            });
        });
    }
    protected function registerTokenDriver()
    {
        MultiTenant::resolved(function ($multitenant){
            $multitenant->registerDriver('token',function ($app,$name, array $config){

            });
        });
    }


/*

    protected function registerCommands()
    {
        $this->app->singleton(Migrate::class,function ($app){
            return new Migrate($app->make('migrator'),$app->make(DatabaseCreator::class));
        });

        $this->app->singleton(MigrateRollback::class,function ($app){
            return new MigrateRollback($app->make('migrator'),$app->make(DatabaseCreator::class));
        });

        $this->app->singleton(Seed::class,function ($app){
            return new Seed($app->make('db'),$app->make(DatabaseCreator::class));
        });
    }


    protected function setupCommands()
    {
        $this->commands([
            Migrate::class,
            MigrateRollback::class,
            Seed::class,
        ]);
    }*/
}
