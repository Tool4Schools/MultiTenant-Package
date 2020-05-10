<?php


namespace Tools4Schools\MultiTenant\Exceptions;

use Exception;

class IdentificationException extends Exception
{
    /**
     * All of the drivers that were checked.
     *
     * @var array
     */
    protected $drivers;

    /**
     * The path the user should be redirected to.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @param  array  $driver
     * @param  string|null  $redirectTo
     * @return void
     */
    public function __construct($message = 'Tenant Not Identified.', array $driver = [], $redirectTo = null)
    {
        parent::__construct($message);

        $this->drivers = $driver;
        $this->redirectTo = $redirectTo;
    }

    /**
     * Get the guards that were checked.
     *
     * @return array
     */
    public function drivers()
    {
        return $this->drivers;
    }

    /**
     * Get the path the user should be redirected to.
     *
     * @return string
     */
    public function redirectTo()
    {
        return $this->redirectTo;
    }
}
