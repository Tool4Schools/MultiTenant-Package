<?php


namespace Tools4Schools\MultiTenant\Http\Middleware;

use Closure;
use Tools4Schools\MultiTenant\Exceptions\IdentificationException;
use Tools4Schools\MultiTenant\Contracts\TenantManager;

class IdentifyTenant
{

    protected $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle($request, Closure $next, ...$drivers)
    {
        try{
            $this->resolveTenant($request,$drivers);
        }catch (IdentificationException $e)
        {
            return redirect()->route('tenant.selection');
        }

        return $next($request);
    }

    protected function resolveTenant($request,array $drivers)
    {
        if(empty($drivers))
        {
            $drivers = [null];
        }

        foreach ($drivers as $driver)
        {

            if($this->manager->driver($driver)->check())
            {
                return $this->manager->shouldUse($driver);
            }

            $this->unidentified($request,$drivers);
        }
    }

    protected function unidentified($request,array $drivers)
    {
        throw new IdentificationException(' Tenant Unidentified.',$drivers,$this->redirectTo($request));
    }

    protected function redirectTo($request)
    {

    }
}