<?php


namespace Tools4Schools\MultiTenant\Drivers;


use Symfony\Component\HttpFoundation\Request;
use Tools4Schools\MultiTenant\Contracts\TenantProvider;
use Tools4Schools\MultiTenant\Models\Tenant;

abstract class TenantDriver
{

    /**
     * The currently selected tenant.
     *
     * @var \Tools4Schools\MultiTenant\Contracts\Tenant
     */
    protected $tenant;

    /**
     * The tenant provider implementation.
     *
     * @var \Tools4Schools\MultiTenant\Contracts\TenantProvider
     */
    protected $provider;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    public function __construct($name,TenantProvider $provider, Request $request = null)
    {
        $this->name = $name;
        $this->request = $request;
        $this->provider = $provider;
    }


    public abstract function tenant():?Tenant;

    /**
     * Determine if the tenant has been identified
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->tenant());
    }


    protected function fireTenantSwitchedEvent($tenant)
    {
        if(isset($this->events))
        {
            $this->events->dispatch(new TenantSwitched($tenant));
        }
    }

}