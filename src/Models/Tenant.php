<?php


namespace Tools4Schools\MultiTenant\Models;


use Illuminate\Database\Eloquent\Model;
use Tools4Schools\MultiTenant\Traits\BelongsToSystem;
use Tools4Schools\MultiTenant\Traits\IsTenant;
use Tools4Schools\Users\Models\User;
use \Tools4Schools\MultiTenant\Contracts\Tenant as TenantContract;

class Tenant extends Model implements TenantContract
{
    use IsTenant,BelongsToSystem;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];


    /**
     * Get the name of the unique identifier for the tenant.
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

    /**
     * @inheritDoc
     */
   /* public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * @inheritDoc
     */
   /* public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * @inheritDoc
     */
  /*  public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }*/
}