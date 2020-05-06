<?php


namespace Tools4Schools\MultiTenant\Contracts;


interface Tenant
{
    /**
     * Get the name of the unique identifier for the tenant.
     *
     * @return string
     */
    public function getTenantIdentifierName();

    /**
     * Get the unique identifier for the tenant.
     *
     * @return mixed
     */
    public function getTenantIdentifier();

    /**
     * Get the token value for the "remember tenant" session.
     *
     * @return string
     */
    //public function getRememberToken();

    /**
     * Set the token value for the "remember tenant" session.
     *
     * @param  string  $value
     * @return void
     */
    //public function setRememberToken($value);

    /**
     * Get the column name for the "remember tenant" token.
     *
     * @return string
     */
    //public function getRememberTokenName();

}