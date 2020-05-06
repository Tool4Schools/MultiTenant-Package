<?php


namespace Tools4Schools\MultiTenant\Drivers;

use Illuminate\Session\Store as Session;
use Symfony\Component\HttpFoundation\Request;
use Tools4Schools\MultiTenant\Contracts\Tenant;
use Tools4Schools\MultiTenant\Contracts\TenantProvider;
use Tools4Schools\MultiTenant\Contracts\TenantDriver as TenantDriverContract;

class SessionDriver extends TenantDriver implements TenantDriverContract
{


    /**
     * The session used by the driver.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * The Illuminate cookie creator service.
     *
     * @var \Illuminate\Contracts\Cookie\QueueingFactory
     */
    protected $cookie;




    public function __construct($name,TenantProvider $provider,Session $session, Request $request = null)
    {
        parent::__construct($name,$provider,$request);

        $this->session = $session;
    }

    public function tenant():?Tenant
    {
        // If we've already retrieved the tenant for the current request we can just
        // return it back immediately. We do not want to fetch the tenant data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->tenant)) {
            return $this->tenant;
        }

        $id = $this->session->get($this->getName());


        // First we will try to load the tenant using the identifier in the session if
        // one exists. Otherwise we will check for a "remember tenant" cookie in this
        // request, and if one exists, attempt to retrieve the tenant using that.
        if (! is_null($id) && $this->tenant = $this->provider->retrieveById($id)) {
            $this->fireTenantSwitchedEvent($this->tenant);
        }

        // If the tenant is null, but we decrypt a "recaller" cookie we can attempt to
        // pull the tenant data on that cookie which serves as a remember cookie on
        // the application. Once we have a user we can return it to the caller.
        if (is_null($this->tenant) && ! is_null($recaller = $this->recaller())) {
            $this->tenant = $this->tenantFromRecaller($recaller);

            if ($this->tenant) {
                $this->switchTenant($this->tenant);
            }
        }

        return $this->tenant;
    }

    public function getName()
    {
        return 'tenant_'.$this->name.'_'.sha1(static::class);
    }

    protected function recaller()
    {
        if(is_null($this->request))
        {
            return;
        }
    }

    public function switchTenant(Tenant $tenant)
    {
        $this->updateSession($tenant->getTenantIdentifier());

        $this->tenant = $tenant;

        $this->fireTenantSwitchedEvent($tenant);
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string  $id
     * @return void
     */
    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);

        $this->session->migrate(true);

    }
}