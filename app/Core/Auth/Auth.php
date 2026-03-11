<?php

namespace App\Core\Auth;

use App\Core\Session\Session;

use App\Models\User;

class Auth
{
    /**
     * Check if the user is authenticated
     */
    public static function check()
    {
        return session()->has('auth_user_id');
    }

    /**
     * Get the authenticated user
     */
    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        static $user = null;
        if ($user === null) {
            $user = User::find(session()->get('auth_user_id'));
        }

        return $user;
    }

    /**
     * Get the authenticated user's ID
     */
    public static function id()
    {
        return session()->get('auth_user_id');
    }

    /**
     * Log in a user
     */
    public static function login(User $user, $remember = false)
    {
        session()->set('auth_user_id', $user->id);
        
        if ($remember) {
            // Implementation for remember me cookie would go here
            // For now, we'll just stick to session
        }

        return true;
    }

    /**
     * Log out the current user
     */
    public static function logout()
    {
        session()->remove('auth_user_id');
        session()->destroy(); // Optional: destroy entire session on logout
        return true;
    }

    /**
     * Attempt to authenticate a user
     */
    public static function attempt(array $credentials, $remember = false)
    {
        $email = $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';

        $user = User::where('email', $email)->first();

        if ($user && password_verify($password, $user->password)) {
            if ($user->status === 'active') {
                self::login($user, $remember);
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the authenticated user has a specific permission
     */
    public static function userCan(string $permission): bool
    {
        $user = self::user();
        return $user ? $user->hasPermission($permission) : false;
    }
}
