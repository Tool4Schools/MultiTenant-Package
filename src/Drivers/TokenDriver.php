<?php


namespace Tools4Schools\MultiTenant\Drivers;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Tools4Schools\MultiTenant\Contracts\Tenant;
use Tools4Schools\MultiTenant\Contracts\TenantDriver as TenantDriverContract;
use Tools4Schools\MultiTenant\Contracts\TenantProvider;

class TokenDriver extends TenantDriver implements  TenantDriverContract
{

    public function tenant() : ?Tenant
    {
        // If we've already retrieved the tenant for the current request we can just
        // return it back immediately. We do not want to fetch the tenant data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->tenant)) {
            return $this->tenant;
        }

        $this->switchTenant(Auth::user()->token()->tenant);

        return $this->tenant;
    }

    public function switchTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;

        $this->fireTenantSwitchedEvent($tenant);
    }
}