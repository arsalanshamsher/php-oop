<?php

namespace App\Core\Support;

use App\Core\Log\Logger;

class Log
{
    protected static ?Logger $instance = null;

    protected static function getInstance(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public static function info($message, array $context = []): void
    {
        self::getInstance()->info($message, $context);
    }

    public static function error($message, array $context = []): void
    {
        self::getInstance()->error($message, $context);
    }

    public static function warning($message, array $context = []): void
    {
        self::getInstance()->warning($message, $context);
    }

    public static function debug($message, array $context = []): void
    {
        self::getInstance()->debug($message, $context);
    }
}
