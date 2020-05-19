<?php


namespace Tools4Schools\MultiTenant\Traits\Console;


trait FetchesTenants
{
    public function tenants($ids=null)
    {
        $tenants =  (config('multitenant.providers.db-tenants.model'))::query();

        if($ids)
        {
            $tenants = $tenants->whereIn('id',$ids);
        }

        return $tenants;
    }
}