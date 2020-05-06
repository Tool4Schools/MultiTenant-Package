<?php


namespace Tools4Schools\MultiTenant\Drivers;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\CryptTrait;
use Symfony\Component\HttpFoundation\Request;
use Tools4Schools\MultiTenant\Contracts\Tenant;
use Tools4Schools\MultiTenant\Contracts\TenantDriver as TenantDriverContract;
use Tools4Schools\MultiTenant\Contracts\TenantProvider;
use App\Models\Passport\AuthCode;

class AuthCodeDriver extends TenantDriver implements TenantDriverContract
{
    use CryptTrait;

    // need to add $privateKey or add function to load key
    public function __construct($name, TenantProvider $provider, Request $request)
    {
        parent::__construct($name, $provider, $request);

        $this->setPrivateKey($this->makeCryptKey('private'));
    }


    public function tenant(): ?Tenant
    {

        // load encryption keys
        https://github.com/laravel/passport/blob/b9764582d06dd2327eb4d1d267c4dfe7861ceb5a/src/PassportServiceProvider.php#L244




        // If we've already retrieved the tenant for the current request we can just
        // return it back immediately. We do not want to fetch the tenant data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->tenant)) {
            return $this->tenant;
        }

        // check if a code is sent
        if(!is_null($encryptedAuthCode = $this->request->input('code')))
        {
            $authCodePayload = \json_decode($this->decrypt($encryptedAuthCode));

            dd($authCodePayload);
            /*$code = AuthCode::where('id',$authCode)
                ->where('client_id',$this->request->input('client_id'))
                ->with('tenant')
                ->first();
            dump($code);*/
           // $this->switchTenant($code->tenant);
        }
        // check if the code has a tenant_id
        // if yes set to tenant
        // if not return null
        return $this->tenant;
    }

    public function switchTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;

        $this->fireTenantSwitchedEvent($tenant);
    }


    /**
     * Create a CryptKey instance without permissions check.
     *
     * @param  string  $key
     * @return \League\OAuth2\Server\CryptKey
     */
    protected function makeCryptKey($type)
    {
        $key = str_replace('\\n', "\n", config('passport.'.$type.'_key'));

        if (! $key) {
            $key = 'file://'.Passport::keyPath('oauth-'.$type.'.key');
        }

        return new CryptKey($key, null, false);
    }
}

