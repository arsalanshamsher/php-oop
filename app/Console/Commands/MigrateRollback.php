<?php

namespace App\Console\Commands;

use App\Core\Database\DatabaseConnection as Database;
use PDO;
use Exception;

class MigrateRollback
{
    protected $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function execute($params)
    {
        // Get last batch migrations
        $migrations = $this->getLastBatchMigrations();

        if (empty($migrations)) {
            echo "Nothing to rollback.\n";
            return;
        }

        $migrationsDir = __DIR__ . '/../../../database/migrations';
        $rolledBack = 0;

        foreach ($migrations as $migration) {
            $migrationName = $migration['migration'];
            $file = sprintf('%s/%s.php', $migrationsDir, $migrationName);

            if (!file_exists($file)) {
                echo sprintf("Warning: Migration file not found: %s\n", $migrationName);
                continue;
            }

            echo sprintf("Rolling back: %s\n", $migrationName);

            try {
                // Run the down method
                $migrationInstance = require $file;
                $migrationInstance->down();

                // Remove from migrations table
                $this->removeMigration($migrationName);

                echo sprintf("Success: Rolled back: %s\n", $migrationName);
                $rolledBack++;
            } catch (Exception $e) {
                echo sprintf("Error: %s\n", $e->getMessage());
                break;
            }
        }

        if ($rolledBack > 0) {
            echo sprintf("\nTotal rolled back: %d migration(s).\n", $rolledBack);
        }
    }

    protected function getLastBatchMigrations()
    {
        // Get the last batch number
        $sql = "SELECT MAX(batch) as max_batch FROM migrations";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        $lastBatch = $result['max_batch'] ?? 0;

        if ($lastBatch === 0) {
            return [];
        }

        // Get all migrations from last batch
        $sql = "SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$lastBatch]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function removeMigration($migration)
    {
        $sql = "DELETE FROM migrations WHERE migration = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$migration]);
    }
}
