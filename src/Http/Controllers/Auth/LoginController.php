<?php

namespace  Tools4Schools\MultiTenant\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Two\InvalidStateException;
use Socialite;
use Tools4Schools\MultiTenant\TenantManager;

class LoginController extends Controller
{

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('tools4schools')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {

        try {
            $socialiteUser = Socialite::driver('tools4schools')->user();
        } catch (InvalidStateException $exception) {

        }

        if(count($socialiteUser->tenants) > 1)
        {
            // user belongs to more than one tenant so lets set the access token in a cookie and redirect to tenant selection
            session(['tmp_user'=> $socialiteUser]);
            return redirect()->route('tenant.selection');
        }
        // if user has more than one tenant ask them to select one
        // else check to see if there is a user in the tenants database with the user uuid

       TenantManager::switchTenant($socialiteUser->tenants[0]);

        return $this->loginUser($socialiteUser);
    }

    protected function loginUser($user)
    {
        dd($user);
        try{
            //$user = User::findByUUID($socialiteUser->uuid);
        }catch (ModelNotFoundException $exception)
        {
            return $this->userNotFound();
        }

        return $this->userFound($user,$socialiteUser);
    }

    private function userNotFound()
    {
        // redirect to portal.tools4schools.ie with error message user account does not have permission to view this page
        // if you think this is an error please contact your school administrator
        // if you are the school administrator then please contact tools4schools support with the below error details

    }

    public function tenantSelection()
    {

    }

    public function logout()
    {
        return Auth::logout();
    }
}
