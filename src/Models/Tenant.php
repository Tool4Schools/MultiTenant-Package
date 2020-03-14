<?php


namespace Tools4Schools\MultiTenant\Models;


use Illuminate\Database\Eloquent\Model;
use Tools4Schools\Users\Models\User;

class Tenant extends Model
{

    public function tenantConnection()
    {
        return $this->hasOne(TenantConnection::class,'tenant_id','id');
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
}