<?php


namespace Tools4Schools\MultiTenant\Models;


use Illuminate\Database\Eloquent\Model;
use Tools4Schools\Users\Models\User;
use \Tools4Schools\MultiTenant\Contracts\Tenant as TenantContract;

class Tenant extends Model implements TenantContract
{

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getTenantIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getTenantIdentifier()
    {
        return $this->{$this->getTenantIdentifierName()};
    }






    public function users()
    {
        return $this->hasMany(User::class);
    }
}