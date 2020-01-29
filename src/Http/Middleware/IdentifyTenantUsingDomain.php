<?php


namespace Tools4Schools\MultiTenant\Http\Middleware;

use Closure;

use Tools4Schools\MultiTenant\TenantManager;

class IdentifyTenantUsingDomain
{
    protected $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle($request, Closure $next)
    {
        $tenant = $this->resolveTenant($request->getHost());

        if(!$tenant)
        {
            return $next($request);
        }

        if(!auth()->user()->tenants->contains('id',$tenant->id))
        {
            return redirect('home');
        }

        event(new TenantIdentified($tenant));

        return $next($request);
    }

    protected function resolveTenant($fqdn)
    {
        return Domain::where('domain',$fqdn)->with('tenant')->first()->tenant();
    }
}