<?php


namespace Tools4Schools\MultiTenant\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tools4Schools\MultiTenant\Contracts\Tenant;

class TenantSwitched
{
    use Dispatchable, SerializesModels;


    public $tenant;
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