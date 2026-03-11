<?php

namespace App\Providers;

use App\Core\Routing\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Load web and api routes
        require_once APP_ROOT . '/routes/web.php';
        require_once APP_ROOT . '/routes/api.php';
    }
}
