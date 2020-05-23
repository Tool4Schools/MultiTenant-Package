<?php


namespace Tools4Schools\MultiTenant\Traits;


use Tools4Schools\MultiTenant\Models\Tenant;

trait BelongsToTenant
{
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class,'tenant_users');
    }
}