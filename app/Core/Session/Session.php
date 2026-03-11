<?php

namespace App\Core\Session;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }



    // Alias so existing code can call session()->set(...)
    public static function set($key, $value)
    {
        self::put($key, $value);
    }

    // Alias so existing code can call session()->remove(...)
    public static function remove($key)
    {
        self::forget($key);
    }

    // Alias so existing code can call session()->destroy()
    public static function destroy()
    {
        if (session_status() !== PHP_SESSION_NONE) {
            // Clear session variables and destroy the session
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }
            session_destroy();
        }
    }


    // ✅ Flash session ko set karne ka method (Laravel jesa)
    public static function flash($key, $value)
    {
        $_SESSION[$key] = $value;
        // Mark as "new" flash for this request. It will be available on next request
        $_SESSION['_flash']['new'][$key] = true;
    }

    // ✅ Normal session set karne ka method
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // ✅ Session se value retrieve karne ka method
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    // ✅ Check karega ke koi session variable hai ya nahi
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    // ✅ Session remove karega (Laravel `session()->forget()`)
    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    // ✅ Laravel jese old input store karega

    public static function flashOldInput()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['old'] = $_POST;
            // Mark the 'old' key as new flash so it survives for the next request
            $_SESSION['_flash']['new']['old'] = true;
        }
    }


    /**
     * Sweep flash data at the start of each request.
     *
     * Behavior:
     * - Remove any flash keys marked as 'old' (these were set in previous request)
     * - Promote 'new' flash keys to 'old' so they will be removed on the next request
     */
    public static function sweep()
    {
        if (!isset($_SESSION['_flash'])) return;

        // Remove keys that were marked old (from previous request)
        if (isset($_SESSION['_flash']['old'])) {
            foreach ($_SESSION['_flash']['old'] as $key => $_v) {
                if (isset($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                }
            }
        }

        // Promote new -> old and clear new markers
        $_SESSION['_flash']['old'] = $_SESSION['_flash']['new'] ?? [];
        unset($_SESSION['_flash']['new']);

        // If there are no markers left, remove the container
        if (empty($_SESSION['_flash']['old'])) {
            unset($_SESSION['_flash']);
        }
    }


    // ✅ Flash session ko **next request ke baad** clear karega

    public static function clearFlash()
    {
        // Backwards-compatible alias that removes old flash immediately (not recommended)
        if (!isset($_SESSION['_flash'])) return;

        foreach ($_SESSION['_flash'] as $group) {
            foreach ($group as $key => $_v) {
                if (isset($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                }
            }
        }

        unset($_SESSION['_flash']);
    }
}


/**
 * Get the session service instance.
 */
function session()
{
    return new \App\Core\Session\Session();
}
