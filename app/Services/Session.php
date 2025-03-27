<?php

namespace App\Services;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            file_put_contents('session_debug.txt', "Session started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        } else {
            file_put_contents('session_debug.txt', "Session already started\n", FILE_APPEND);
        }
    }


    // ✅ Flash session ko set karne ka method (Laravel jesa)
    public static function flash($key, $value)
    {
        $_SESSION[$key] = $value;
        $_SESSION['_flash'][$key] = true; // Flash data ko mark karna zaroori hai
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
            file_put_contents('session_debug.txt', "flashOldInput() called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

            $_SESSION['old'] = $_POST;
            $_SESSION['_flash']['old'] = true;

            file_put_contents('session_debug.txt', "Old Input Stored: " . print_r($_SESSION['old'], true) . "\n", FILE_APPEND);
        }
    }



    // ✅ Flash session ko **next request ke baad** clear karega

    public static function clearFlash()
    {
        file_put_contents('session_debug.txt', "clearFlash() called at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        if (!isset($_SESSION['_flash'])) return;

        foreach ($_SESSION['_flash'] as $key => $value) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]); // ✅ Flash session data ko remove karega
            }
        }

        unset($_SESSION['_flash']); // ✅ Flash session ke markers ko bhi remove karega
    }
}

// ✅ Session start hamesha honi chahiye
Session::start();
