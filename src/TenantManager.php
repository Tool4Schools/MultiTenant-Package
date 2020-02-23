<?php


namespace Tools4Schools\MultiTenant;

use InvalidArgumentException;
use Tools4Schools\MultiTenant\Models\Tenant;
use \Tools4Schools\MultiTenant\Contracts\TenantManager as TenantManagerContract;

class TenantManager implements TenantManagerContract
{
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
     * @return Tenant
     */
    public function tenant():Tenant
    {

    }

    public function driver($name = null)
    {
        $name = $name?: $this->getDefaultDriver();

        return isset($this->drivers[$name])
            ? $this->drivers[$name]
            : $this->drivers[$name] = $this->resolve($name);
    }

    public function shouldUse($name): void
    {
        // TODO: Implement shouldUse() method.
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Tenant Driver [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($name, $config);
        }

        //throw new InvalidArgumentException("Tenant Driver driver [{$name}] is not defined.");
    }

    public function registerDriver($driver,\Closure $callback)
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    /**
     * Call a custom driver creator.
     *
     * @param  string  $name
     * @param  array  $config
     * @return mixed
     */
    protected function callCustomCreator($name, array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $name, $config);
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