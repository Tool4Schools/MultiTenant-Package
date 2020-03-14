<?php


namespace Tools4Schools\MultiTenant\Http\Controllers;

use  Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tools4Schools\MultiTenant\Contracts\TenantManager;

class TenantController extends Controller
{
    protected $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(Request $request)
    {
       /* $user = $request->session()->get('tmp_user',function(){
            // get tenants with access token
            return 'bla';
        });

        dump($user);
       // return view('tenant::selection',$user);*/

       dd($this->manager->driver());
    }

    public function select(Request $request)
    {
        $this->manager->switchTenant($request->tenantUUID);

        // complete login
        return redirect()->intended('dashboard');
    }
}