<?php


namespace Tools4Schools\MultiTenant;

use InvalidArgumentException;
use Illuminate\Support\Facades\Route;
use Tools4Schools\MultiTenant\Models\Tenant;
use Tools4Schools\MultiTenant\Contracts\TenantManager as TenantManagerContract;
use Tools4Schools\MultiTenant\Traits\CreateTenantProvider;


class TenantManager implements TenantManagerContract
{
    use CreateTenantProvider;
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of created drivers
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Create a new Tenant manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * Binds the Passport routes into the controller.
     *
     * @param  callable|null  $callback
     * @param  array  $options
     * @return void
     */
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->all();
        };

        $defaultOptions = [
            //'namespace' => '\Tools4Schools\MultiTenant\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }


    public function driver($name = null)
    {
        $name = $name?: $this->getDefaultDriver();

        return $this->drivers[$name] ?? $this->drivers[$name] = $this->resolve($name);
    }

    public function provider()
    {

    }

    public function shouldUse($name): void
    {
        $name = $name?: $this->getDefaultDriver();

        $this->setDefaultDriver($name);

        $this->tenantResolver = function ($name = null){

            return $this->driver($name)->tenant();
        };

    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Tenant Driver [{$name}] is not defined.");
        }

        if (isset($this->customDriverCreators[$config['driver']])) {
            return $this->callCustomDriverCreator($name, $config);
        }

        throw new InvalidArgumentException("Tenant Driver driver [{$name}] is not defined.");
    }

    public function registerDriver($driver,\Closure $callback)
    {
        $this->customDriverCreators[$driver] = $callback;

        return $this;
    }



    /**
     * Call a custom driver creator.
     *
     * @param  string  $name
     * @param  array  $config
     * @return mixed
     */
    protected function callCustomDriverCreator($name, array $config)
    {

        $provider = $this->createTenantProvider($config['provider']);


        return $this->customDriverCreators[$config['driver']]($this->app, $name, $provider, $config);
    }


    protected function getConfig($name)
    {
        return $this->app['config']["multitenant.drivers.{$name}"];
    }

    /**
     * Get the default tenant driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['multitenant.defaults.driver'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['multitenant.defaults.driver'] = $name;
    }

    public function __call($method, $arguments)
    {
       return $this->driver()->{$method}(...$arguments);
    }
}
