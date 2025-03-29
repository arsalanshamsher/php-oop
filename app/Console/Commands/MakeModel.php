<?php

namespace App\Console\Commands;

class MakeModel
{
    public function execute($params)
    {
        $name = $params[0] ?? null;

        if (!$name) {
            echo "Error: Model name required.\n";
            return;
        }

        $template = file_get_contents(__DIR__ . '/../stubs/model.stub');
        $content = str_replace('{{modelName}}', $name, $template);

        $path = __DIR__ . "/../../Models/{$name}.php";
        file_put_contents($path, $content);

        echo "✅ Model Created: app/Models/{$name}.php\n";
    }
}
