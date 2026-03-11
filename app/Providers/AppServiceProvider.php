<?php

namespace App\Providers;

use App\Core\Exceptions\ErrorHandler;
use App\Core\Session\Session;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register core services if needed (e.g., in a container)
    }

    public function boot()
    {
        // Set up global exception handler
        set_exception_handler([ErrorHandler::class, 'handle']);
        set_error_handler([ErrorHandler::class, 'handleError']);
        register_shutdown_function([ErrorHandler::class, 'handleShutdown']);

        // Start session and handle flash lifecycle
        Session::start();
        Session::sweep();
        Session::flashOldInput();
    }
}
