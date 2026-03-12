<?php

namespace App\Core\Log;

class Logger
{
    protected string $logPath;

    public function __construct()
    {
        $this->logPath = APP_ROOT . '/storage/logs/app.log';
    }

    /**
     * Log an informative message.
     */
    public function info($message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    /**
     * Log an error message.
     */
    public function error($message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    /**
     * Log a warning message.
     */
    public function warning($message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    /**
     * Log a debug message.
     */
    public function debug($message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    /**
     * Core logging function.
     */
    protected function log(string $level, $message, array $context = []): void
    {
        $directory = dirname($this->logPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!is_string($message)) {
            $message = json_encode($message, JSON_PRETTY_PRINT);
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[$timestamp] $level: $message$contextString" . PHP_EOL;

        file_put_contents($this->logPath, $logMessage, FILE_APPEND);
    }
}
