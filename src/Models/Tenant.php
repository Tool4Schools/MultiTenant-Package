<?php


namespace Tools4Schools\MultiTenant\Models;


use Illuminate\Database\Eloquent\Model;
use Tools4Schools\Users\Models\User;

class Tenant extends Model
{

    public function users()
    {
        return $this->hasMany(User::class);
    }
}