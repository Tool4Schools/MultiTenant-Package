<?php


namespace Tools4Schools\MultiTenant\Traits;


trait IsTenant
{
    /**
     * The column name of the "remember token" token.
     *
     * @var string
     */
    protected $rememberTokenName = 'remember_token';

    /**
     * Get the name of the unique identifier for the tenant.
     *
     * @return string
     */
    public function getTenantIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the tenant.
     *
     * @return mixed
     */
    public function getTenantIdentifier()
    {
        return $this->{$this->getTenantIdentifierName()};
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        if (! empty($this->getRememberTokenName())) {
            return (string) $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }
}