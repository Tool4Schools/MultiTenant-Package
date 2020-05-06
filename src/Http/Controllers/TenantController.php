<?php


namespace Tools4Schools\MultiTenant\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use  Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tools4Schools\MultiTenant\Contracts\TenantManager;
use Tools4Schools\MultiTenant\Models\Tenant;
use Tools4Schools\Users\Models\User;
use App\Providers\RouteServiceProvider;

class TenantController extends Controller
{
    use RedirectsUsers;

    protected $manager;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(Request $request)
    {
        $tenants  = auth()->user()->tenants;

        if($tenants->count() == 1){

            $this->manager->switchTenant($tenants[0]);

            return redirect()->intended('/home');
        }

        return view('tenant::selection',['tenants'=>$tenants]);

    }

    public function select(Request $request)
    {
        $tenant = Tenant::where('uuid',$request->input('tenant'))->firstOrFail();
        $this->manager->switchTenant($tenant);

        return redirect()->intended($this->redirectPath());
    }
}