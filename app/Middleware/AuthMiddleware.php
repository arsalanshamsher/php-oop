<?php

namespace App\Middleware;

use App\Core\Auth\Auth;
use App\Core\Http\Middleware;

class AuthMiddleware extends Middleware
{
    /**
     * Handle the incoming request for web authentication
     *
     * @param  mixed $request
     * @param  callable $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            // Using redirect helper; the object will call header() in its __destruct()
            redirect()->route('login')->to(route('login'));
            // Return early — redirect object destructor will send the header when out of scope
            return null;
        }

        // Continue to next middleware / handler
        return $next($request);
    }
}
