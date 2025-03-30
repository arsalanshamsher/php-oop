<?php

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct($routeName)
    {
        parent::__construct("🚨 Route [{$routeName}] not defined.");
    }
}
