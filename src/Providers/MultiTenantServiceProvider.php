<?php

namespace Tools4Schools\MultiTenant\Providers;


use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Tools4Schools\MultiTenant\Contracts\Manager;
use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenant;
use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingDomain;
use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingSession;
use Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenantUsingToken;
use Tools4Schools\MultiTenant\Middleware\LoadTenantUsingSession;
use Tools4Schools\MultiTenant\TenantManager;
use Tools4Schools\MultiTenant\Contracts\TenantProvider as TenantRepositoryContract;
use Tools4Schools\MultiTenant\Repositories\Api\TenantProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

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
       // $this->loadViewsFrom(__DIR__.'/../../resources/views','multitenant');

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

        $this->registerManager();
        //$this->app->alias(TenantManager::class,'multitenant');

        //$this->registerCommands();
    }


    protected function registerManager()
    {

        $this->app->singleton('tenantmanager', function ($app){
            return new TenantManager($app);
        });

        //$this->app->alias(TenantManager::class,Manager::class);
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
