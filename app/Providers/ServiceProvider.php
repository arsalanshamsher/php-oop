<?php

namespace App\Providers;

abstract class ServiceProvider
{
    /**
     * Register any application services.
     */
    abstract public function register();

    /**
     * Bootstrap any application services.
     */
    abstract public function boot();
}
