<?php

namespace App\Core\Database;

use PDO;

class Blueprint
{
    protected $table;
    protected $columns = [];
    protected $pdo;
    protected $lastColumn;
    protected $foreignKeys = [];
    protected $lastForeignKey;

    protected $isAlter = false;
    protected $dropColumns = [];

    public function __construct($table, PDO $pdo, $isAlter = false)
    {
        $this->table = $table;
        $this->pdo = $pdo;
        $this->isAlter = $isAlter;
    }

    protected function addColumn($name, $definition)
    {
        $this->columns[$name] = [
            'definition' => $definition,
            'after' => null
        ];
        $this->lastColumn = $name;
        return $this;
    }

    public function id()
    {
        return $this->addColumn('id', "BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY");
    }

    public function foreignId($name)
    {
        $this->addColumn($name, "BIGINT UNSIGNED NOT NULL");
        $this->lastForeignKey = $name;
        return $this;
    }

    public function constrained($table = null, $column = 'id')
    {
        if (!$table && $this->lastForeignKey) {
            // Guess table name from column name: user_id -> users
            $table = str_replace('_id', 's', $this->lastForeignKey);
        }

        $this->foreignKeys[] = [
            'column' => $this->lastForeignKey,
            'referenced_table' => $table,
            'referenced_column' => $column,
            'on_delete' => null
        ];

        $this->lastForeignKey = null; // Reset
        return $this;
    }

    public function foreign($column)
    {
        $this->lastForeignKey = is_array($column) ? $column[0] : $column;
        return $this;
    }

    public function references($column)
    {
        $this->columnsToReferences = is_array($column) ? $column[0] : $column;
        return $this;
    }

    public function on($table)
    {
        $this->foreignKeys[] = [
            'column' => $this->lastForeignKey,
            'referenced_table' => $table,
            'referenced_column' => $this->columnsToReferences ?? 'id',
            'on_delete' => null
        ];
        
        $this->lastForeignKey = null;
        $this->columnsToReferences = null;
        return $this;
    }

    public function onDelete($action)
    {
        if (!empty($this->foreignKeys)) {
            $lastIndex = count($this->foreignKeys) - 1;
            $this->foreignKeys[$lastIndex]['on_delete'] = strtoupper($action);
        }
        return $this;
    }

    public function string($name, $length = 255)
    {
        return $this->addColumn($name, "VARCHAR($length) NOT NULL");
    }

    public function enum($name, array $allowed)
    {
        $allowedStr = implode(', ', array_map(fn($val) => "'$val'", $allowed));
        return $this->addColumn($name, "ENUM($allowedStr) NOT NULL");
    }

    public function text($name)
    {
        return $this->addColumn($name, "TEXT NOT NULL");
    }

    public function integer($name)
    {
        return $this->addColumn($name, "INT NOT NULL");
    }

    public function bigInteger($name)
    {
        return $this->addColumn($name, "BIGINT NOT NULL");
    }

    public function unsignedBigInteger($name)
    {
        return $this->addColumn($name, "BIGINT UNSIGNED NOT NULL");
    }

    public function decimal($name, $precision = 8, $scale = 2)
    {
        return $this->addColumn($name, "DECIMAL($precision, $scale) NOT NULL");
    }

    public function float($name)
    {
        return $this->addColumn($name, "FLOAT NOT NULL");
    }

    public function boolean($name)
    {
        return $this->addColumn($name, "TINYINT(1) NOT NULL DEFAULT 0");
    }

    public function date($name)
    {
        return $this->addColumn($name, "DATE NOT NULL");
    }

    public function dateTime($name)
    {
        return $this->addColumn($name, "DATETIME NOT NULL");
    }

    public function timestamp($name)
    {
        return $this->addColumn($name, "TIMESTAMP NULL");
    }

    public function timestamps()
    {
        $this->addColumn('created_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->addColumn('updated_at', "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        return $this;
    }

    public function nullable()
    {
        if ($this->lastColumn) {
            $this->columns[$this->lastColumn]['definition'] = str_replace(' NOT NULL', '', $this->columns[$this->lastColumn]['definition']);
            if (strpos($this->columns[$this->lastColumn]['definition'], ' NULL') === false) {
                $this->columns[$this->lastColumn]['definition'] .= " NULL";
            }
        }
        return $this;
    }

    public function after($column)
    {
        if ($this->lastColumn) {
            $this->columns[$this->lastColumn]['after'] = $column;
        }
        return $this;
    }

    public function unique()
    {
        if ($this->lastColumn) {
            $this->columns[$this->lastColumn]['definition'] .= " UNIQUE";
        }
        return $this;
    }

    public function default($value)
    {
        if ($this->lastColumn) {
            $value = is_string($value) ? "'$value'" : $value;
            // Remove existing default if any
            $this->columns[$this->lastColumn]['definition'] = preg_replace('/ DEFAULT .*/', '', $this->columns[$this->lastColumn]['definition']);
            $this->columns[$this->lastColumn]['definition'] .= " DEFAULT $value";
        }
        return $this;
    }

    public function dropColumn($name)
    {
        $names = is_array($name) ? $name : [$name];
        foreach ($names as $n) {
            $this->dropColumns[] = $n;
        }
        return $this;
    }

    public function dropForeign($name)
    {
        // Guess index name if array or string
        $column = is_array($name) ? $name[0] : $name;
        $this->dropForeignKeys[] = "fk_{$this->table}_{$column}";
        return $this;
    }

    public function build()
    {
        if ($this->isAlter) {
            return $this->buildAlter();
        }

        $lines = array_map(function($name, $col) {
            return "`$name` {$col['definition']}";
        }, array_keys($this->columns), $this->columns);

        foreach ($this->foreignKeys as $fk) {
            $line = "CONSTRAINT `fk_{$this->table}_{$fk['column']}` FOREIGN KEY (`{$fk['column']}`) REFERENCES `{$fk['referenced_table']}`(`{$fk['referenced_column']}`)";
            if ($fk['on_delete']) {
                $line .= " ON DELETE {$fk['on_delete']}";
            }
            $lines[] = $line;
        }

        $sql = "CREATE TABLE `{$this->table}` (\n    ";
        $sql .= implode(",\n    ", $lines);
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $sql;
    }

    protected function buildAlter()
    {
        $actions = [];

        foreach ($this->dropForeignKeys ?? [] as $fk) {
            $actions[] = "DROP FOREIGN KEY `$fk`";
        }

        foreach ($this->dropColumns as $column) {
            $actions[] = "DROP COLUMN `$column`";
        }

        foreach ($this->columns as $name => $col) {
            $action = "ADD COLUMN `$name` {$col['definition']}";
            if ($col['after']) {
                $action .= " AFTER `{$col['after']}`";
            }
            $actions[] = $action;
        }

        foreach ($this->foreignKeys as $fk) {
            $line = "ADD CONSTRAINT `fk_{$this->table}_{$fk['column']}` FOREIGN KEY (`{$fk['column']}`) REFERENCES `{$fk['referenced_table']}`(`{$fk['referenced_column']}`)";
            if ($fk['on_delete']) {
                $line .= " ON DELETE {$fk['on_delete']}";
            }
            $actions[] = $line;
        }

        $sql = "ALTER TABLE `{$this->table}`\n    ";
        $sql .= implode(",\n    ", $actions);
        
        return $sql;
    }

    public function execute()
    {
        $sql = $this->build();
        if ($sql) {
            $this->pdo->exec($sql);
        }
    }

}
