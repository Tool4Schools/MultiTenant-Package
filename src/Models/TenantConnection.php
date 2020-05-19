<?php


namespace Tools4Schools\MultiTenant\Models;

use Illuminate\Database\Eloquent\Model;
use Tools4Schools\MultiTenant\Traits\BelongsToSystem;

class TenantConnection extends Model
{
    use BelongsToSystem;
}