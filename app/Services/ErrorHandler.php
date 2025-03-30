<?php

namespace App\Services;

use App\Exceptions\RouteNotFoundException;

class ErrorHandler
{
    public static function handle($exception)
    {
        http_response_code(500);

        if ($exception instanceof RouteNotFoundException) {
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Route Error</title>
                <style>
                    body { font-family: Arial, sans-serif; background: #f8d7da; text-align: center; padding: 50px; }
                    .error-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: inline-block; }
                    h1 { color: #721c24; }
                    p { color: #721c24; font-size: 18px; }
                </style>
            </head>
            <body>
                <div class='error-box'>
                    <h1>🚨 Error</h1>
                    <p>{$exception->getMessage()}</p>
                </div>
            </body>
            </html>";
        } else {
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>{$exception->getMessage()}</p>";
        }

        exit;
    }
}
