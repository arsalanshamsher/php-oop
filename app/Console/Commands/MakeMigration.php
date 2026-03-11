<?php

namespace App\Console\Commands;

class MakeMigration
{
    public function execute($params)
    {
        $name = $params[0] ?? null;

        if (!$name) {
            echo "Error: Migration name required.\n";
            echo "Usage: php artisan make:migration CreateUsersTable\n";
            return;
        }

        // Extract table name from migration name
        $tableName = $this->getTableName($name);
        
        // Generate timestamp
        $timestamp = date('Y_m_d_His');
        
        // Convert to snake_case
        $fileName = $timestamp . '_' . $this->toSnakeCase($name);
        
        // Load stub template
        $template = file_get_contents(__DIR__ . '/../stubs/migration.stub');
        $content = str_replace('{{table}}', $tableName, $template);
        
        // Create migrations directory if it doesn't exist
        $migrationsDir = __DIR__ . '/../../../database/migrations';
        if (!is_dir($migrationsDir)) {
            mkdir($migrationsDir, 0755, true);
        }
        
        $path = "$migrationsDir/{$fileName}.php";
        file_put_contents($path, $content);
        
        echo "✅ Migration Created: database/migrations/{$fileName}.php\n";
    }

    private function getTableName($name)
    {
        // Extract table name from migration name
        // CreateUsersTable -> users
        // AddEmailToUsersTable -> users
        
        $name = str_replace(['Create', 'Table', 'Add', 'To'], '', $name);
        $name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
        return strtolower(trim($name, '_'));
    }

    private function toSnakeCase($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }
}
