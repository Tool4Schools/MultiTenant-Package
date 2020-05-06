<?php


namespace Tools4Schools\MultiTenant\Exceptions;

use Exception;

class IdentificationException extends Exception
{
    /**
     * All of the guards that were checked.
     *
     * @var array
     */
    protected $guards;

    /**
     * Create a new authentication exception.
     *
     * @param  string  $message
     * @param  array  $driver
     * @return void
     */
    public function __construct($message = 'Unauthenticated.', array $driver = [])
    {
        parent::__construct($message);

        $this->guards = $driver;
    }

    /**
     * Get the guards that were checked.
     *
     * @return array
     */
    public function guards()
    {
        return $this->guards;
    }

    public function redirectTo()
    {

    }
}
