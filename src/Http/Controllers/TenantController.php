<?php


namespace Tools4Schools\MultiTenant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tools4Schools\MultiTenant\TenantManager;

class TenantController extends Controller
{
    protected $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function index()
    {
        echo 'load tenants that the user is a member of from ....';
    }

    public function select(Request $request)
    {
        $this->manager->switchTenant($request->tenantUUID);
        return redirect()->intended('dashboard');
    }
}