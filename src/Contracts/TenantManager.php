<?php


namespace Tools4Schools\MultiTenant\Contracts;


interface TenantManager
{
    public function driver($name = null);

    public function shouldUse($name):void ;
}