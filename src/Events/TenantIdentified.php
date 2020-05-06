<?php

namespace Tools4Schools\MultiTenant\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tools4Schools\MultiTenant\Contracts\Tenant;

class TenantIdentified
{
    use Dispatchable, SerializesModels;


    public Tenant $tenant;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }


}
