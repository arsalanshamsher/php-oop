<?php

namespace App\Console\Commands;

use App\Core\Database\DatabaseConnection as Database;
use PDO;

class Migrate
{
    protected $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function execute($params)
    {
        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();

        // Get all migration files
        $migrationsDir = __DIR__ . '/../../../database/migrations';
        
        if (!is_dir($migrationsDir)) {
            echo "No migrations directory found.\n";
            return;
        }

        $files = glob("$migrationsDir/*.php");
        sort($files);

        if (empty($files)) {
            echo "No migrations found.\n";
            return;
        }

        // Get already run migrations
        $ran = $this->getRanMigrations();

        // Get current batch number
        $batch = $this->getNextBatchNumber();

        $migrated = 0;

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');

            // Skip if already run
            if (in_array($migrationName, $ran)) {
                continue;
            }

            echo "Migrating: $migrationName\n";

            try {
                // Run the migration
                $migration = require $file;
                $migration->up();

                // Record in migrations table
                $this->recordMigration($migrationName, $batch);

                echo "✅ Migrated: $migrationName\n";
                $migrated++;
            } catch (\Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "\n";
                break;
            }
        }

        if ($migrated === 0) {
            echo "Nothing to migrate.\n";
        } else {
            echo "\n✅ Migrated $migrated migration(s).\n";
        }
    }

    protected function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->pdo->exec($sql);
    }

    protected function getRanMigrations()
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getNextBatchNumber()
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch();
        return ($result['max_batch'] ?? 0) + 1;
    }

    protected function recordMigration($migration, $batch)
    {
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }
}
