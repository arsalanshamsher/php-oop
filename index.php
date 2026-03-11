<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/vendor/autoload.php';

// Load environment variables
load_env(APP_ROOT . '/.env');

// Initialize and Boot Service Providers
$providers = [
    new AppServiceProvider(),
    new RouteServiceProvider(),
];

foreach ($providers as $provider) {
    $provider->register();
}

foreach ($providers as $provider) {
    $provider->boot();
}

// Handle the request
use App\Core\Routing\Route;
Route::handle();

