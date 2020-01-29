<?php


namespace Tools4Schools\MultiTenant;


use Tools4Schools\MultiTenant\Models\Tenant;

class TenantManager
{
    /**
     * The array of created drivers
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * @return Tenant
     */
    public function tenant():Tenant
    {

    }

    public function driver($name = null)
    {
        $name = $name?: $this->getDefaultDriver();

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