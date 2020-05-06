<?php


namespace Tools4Schools\MultiTenant\Drivers;

use Tools4Schools\MultiTenant\Contracts\TenantDriver as TenantDriverContract;

class TokenDriver extends TenantDriver implements  TenantDriverContract
{

    public function tenant()
    {
        // If we've already retrieved the tenant for the current request we can just
        // return it back immediately. We do not want to fetch the tenant data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->tenant)) {
            return $this->tenant;
        }
    }
}