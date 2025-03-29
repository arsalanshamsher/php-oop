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

        $template = file_get_contents(__DIR__ . '/../stubs/controller.stub');
        $content = str_replace('{{controllerName}}', $name, $template);

        $path = __DIR__ . "/../../Controllers/{$name}.php";
        file_put_contents($path, $content);

        echo "✅ Controller Created: app/Controllers/{$name}.php\n";
    }
}
