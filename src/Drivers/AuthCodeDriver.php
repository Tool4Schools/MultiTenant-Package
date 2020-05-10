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

        $this->setEncryptionKey(app('encrypter')->getKey());
    }


    public function tenant(): ?Tenant
    {

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

            //dump($authCodePayload);
            $code = AuthCode::where('id',$authCodePayload->auth_code_id)
                ->where('client_id',$this->request->input('client_id'))
                ->with('tenant')
                ->first();
            $this->switchTenant($code->tenant);
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

