<?php


namespace Tools4Schools\MultiTenant\Http\Controllers;

use  Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tools4Schools\MultiTenant\TenantManager;

class TenantController extends LoginController
{
    protected $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(Request $request)
    {
        $tenants = $request->session()->get('tmp_user',function(){
            // get tenants with access token
        });

        return view('tenant.selection',$tenants);
    }

    public function select(Request $request)
    {
        $this->manager->switchTenant($request->tenantUUID);

        // complete login
        return redirect()->intended('dashboard');
    }
}