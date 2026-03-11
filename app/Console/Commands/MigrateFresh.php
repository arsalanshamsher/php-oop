<?php

namespace App\Console\Commands;

use App\Config\Database;
use PDO;

class MigrateFresh
{
    protected $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function execute($params)
    {
        echo "Dropping all tables...\n";

        $this->disableForeignKeyChecks();
        $this->dropAllTables();
        $this->enableForeignKeyChecks();

        echo "✅ All tables dropped.\n";

        // Run migrations
        $migrate = new Migrate();
        $migrate->execute($params);
    }

    protected function disableForeignKeyChecks()
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    }

    protected function enableForeignKeyChecks()
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }

    protected function dropAllTables()
    {
        $stmt = $this->pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS `$table` CASCADE");
            echo "Dropped: $table\n";
        }
    }
}
