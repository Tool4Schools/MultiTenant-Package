<?php


namespace Tools4Schools\MultiTenant\Traits;

use InvalidArgumentException;
trait CreateTenantProvider
{
    /**
     * The registered custom provider creators.
     *
     * @var array
     */
    protected $customProviderCreators = [];


    public function createTenantProvider($driver = null)
    {

        if (is_null($config = $this->getProviderConfiguration($driver))) {
            return;
        }

        if (isset($this->customProviderCreators[$config['driver']])) {
            return $this->callCustomProviderCreator($config);
        }

        throw new InvalidArgumentException(
            "Tenant provider [{$config['driver']}] is not defined."
        );
    }



    protected function callCustomProviderCreator(array $config)
    {
        return $this->customProviderCreators[$config['driver']]($this->app, $config);
    }


    public function registerProvider($provider,\Closure $callback)
    {
        $this->customProviderCreators[$provider] = $callback;

        return $this;
    }

    /**
     * Get the user provider configuration.
     *
     * @param  string|null  $provider
     * @return array|null
     */
    protected function getProviderConfiguration($driver)
    {

        if ($driver = $driver ?: $this->getDefaultTenantProvider()) {
            return $this->app['config']['multitenant.providers.'.$driver];
        }
    }

    /**
     * Get the default user provider name.
     *
     * @return string
     */
    public function getDefaultTenantProvider()
    {
        return $this->app['config']['multitenant.defaults.provider'];
    }
}