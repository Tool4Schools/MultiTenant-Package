<?php


namespace Tools4Schools\MultiTenant\Traits;


use Tools4Schools\MultiTenant\Scopes\TenantScope;

trait UsesTenantScope
{
    public static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }
}