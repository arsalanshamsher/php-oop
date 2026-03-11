<?php

namespace App\Console\Commands;

class MakeController
{
    public function execute($params)
    {
        $name = $params[0] ?? null;

        if (!$name) {
            echo "Error: Controller name required.\n";
            return;
        }

        // Extract just the controller name (without path) for the class name
        $controllerName = basename($name);
        
        $template = file_get_contents(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace('{{controllerName}}', $controllerName, $template);

        $path = __DIR__ . "/../../Controllers/{$name}.php";
        
        // Create directory if it doesn't exist
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($path, $content);

        echo "✅ Controller Created: app/Controllers/{$name}.php\n";
    }
}
