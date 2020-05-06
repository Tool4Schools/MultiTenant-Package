<?php


namespace Tools4Schools\MultiTenant\Traits;


use Illuminate\Support\Str;
use Tools4Schools\MultiTenant\Contracts\Tenant;
use Tools4Schools\MultiTenant\Models\TenantConnection;

trait IsTenant
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant){
           $tenant->uuid = Str::uuid();
        });

        static::created(function ($tenant){
            $tenant->tenantConnection()->save(static::newDatabaseConnection($tenant));
        });
    }

    protected static function newDatabaseConnection(Tenant $tenant)
    {
        return new TenantConnection([
            'database' => 't4s_'.$tenant->id,
            'host' =>'',
            'username' =>'',
            'password' =>''
        ]);
    }

    public function tenantConnection()
    {
        return $this->hasOne(TenantConnection::class,'tenant_id','id');
    }

}