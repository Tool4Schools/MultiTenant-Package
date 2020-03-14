<?php


namespace Tools4Schools\MultiTenant\Drivers;

use Illuminate\Session\Store as Session;

class SessionDriver extends TenantDriver
{
    /**
     * The session used by the guard.
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

    public function __construct($name,Session $session, Request $request = null)
    {
        $this->name = $name;
        $this->session = $session;
        $this->request = $request;
       // $this->provider = $provider;
    }

    public function tenant()
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
        /*if (is_null($this->tenant) && ! is_null($recaller = $this->recaller())) {
            $this->tenant = $this->tenantFromRecaller($recaller);

            if ($this->tenant) {
                $this->updateSession($this->tenant->getTenantIdentifier());

                $this->fireTenantSwitchedEvent($this->tenant);
            }
        }*/

        return $this->tenant;
    }

    public function getName()
    {
        return 'tenant_'.$this->name.'_'.sha1(static::class);
    }

    protected function recaller()
    {
        if(is_number($this->request))
        {
            return;
        }
    }
}