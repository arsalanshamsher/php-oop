<?php

namespace App\Console;

class Kernel
{
    protected $commands = [
        'make:model' => \App\Console\Commands\MakeModel::class,
        'make:controller' => \App\Console\Commands\MakeController::class,
        'make:apicontroller' => \App\Console\Commands\MakeApiController::class,
    ];

    public function handle($argv)
    {
        $command = $argv[1] ?? null;
        $params = array_slice($argv, 2);

        if (isset($this->commands[$command])) {
            $commandClass = new $this->commands[$command];
            $commandClass->execute($params);
        } else {
            echo "Command not found: $command\n";
        }
    }
}
