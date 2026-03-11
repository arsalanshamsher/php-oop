<?php

namespace App\Console\Commands;

use App\Config\Database;
use PDO;

class MigrateReset
{
    protected $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function execute($params)
    {
        // Get all migrations from the database in descending order of ID
        $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY id DESC");
        $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($migrations)) {
            echo "Nothing to reset.\n";
            return;
        }

        $migrationsDir = __DIR__ . '/../../../database/migrations';
        $rolledBack = 0;

        foreach ($migrations as $migration) {
            $file = "$migrationsDir/{$migration['migration']}.php";

            if (!file_exists($file)) {
                echo "⚠️  Migration file not found: {$migration['migration']}\n";
                continue;
            }

            echo "Rolling back: {$migration['migration']}\n";

            try {
                $migrationInstance = require $file;
                $migrationInstance->down();

                $this->removeMigration($migration['migration']);

                echo "✅ Rolled back: {$migration['migration']}\n";
                $rolledBack++;
            } catch (\Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "\n";
                break;
            }
        }

        if ($rolledBack > 0) {
            echo "\n✅ Reset $rolledBack migration(s).\n";
        }
    }

    protected function removeMigration($migration)
    {
        $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migration]);
    }
}
