<?php

namespace App\Core\Database;

use App\Core\Database\DatabaseConnection as Database;
use InvalidArgumentException;

class Model implements \JsonSerializable
{
    protected static $table;
    protected $attributes = [];
    protected array $query_where = [];
    protected array $query_with = [];
    protected array $relations = [];
    protected $query_limit = null;
    protected $query_offset = null;
    protected $query_order = null;

    public function __construct($data = [])
    {
        $this->attributes = $data;
    }

    // ✅ Get all records
    public static function all(): array
    {
        return (new static)->get();
    }
    // ✅ Create a new record
    public static function create(array $attributes = [])
    {
        $instance = new static($attributes);
        $instance->save();
        return $instance;
    }

    // ✅ Find by ID
    public static function find($id)
    {
        $db = new Database();
        $result = $db->first(static::$table, ['id' => $id]);

        return $result ? new static($result) : null;
    }

    // ✅ Where with Multiple Conditions (Chaining Support)
    public static function where(string $column, string $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        // Validate operator (optional but recommended)
        $validOperators = ['=', '<', '>', '<=', '>=', '<>', '!=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN'];
        if (!in_array(strtoupper($operator), $validOperators)) {
            throw new InvalidArgumentException("Invalid operator: {$operator}");
        }

        // Use late-static binding so calling User::where(...) returns a User-model instance
        $instance = new static();
        $instance->query_where[] = [$column, $operator, $value];

        return $instance;
    }

    // with
    public static function with($relations)
    {
        $instance = new static();
        $instance->query_with = (array)$relations;
        return $instance;
    }

    public static function query()
    {
        return new static;
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    public function limit($limit)
    {
        $this->query_limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->query_offset = $offset;
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->query_order = "$column $direction";
        return $this;
    }

    // ✅ Get all matching records
    public function get(): array
    {
        $db = new Database();
        $data = $db->select(
            static::$table,
            "*",
            $this->query_where,
            $this->query_order,
            $this->query_limit,
            $this->query_offset
        );

        $models = array_map(fn($row) => new static($row), $data);

        if (!empty($models) && !empty($this->query_with)) {
            $this->eagerLoadRelations($models);
        }

        $this->resetQuery();

        return $models;
    }

    // ✅ Count records
    public function count(): int
    {
        $db = new Database();
        $data = $db->select(
            static::$table,
            "COUNT(*) as total",
            $this->query_where
        );

        $this->resetQuery();

        return (int)($data[0]['total'] ?? 0);
    }

    // ✅ Pluck a single column
    public function pluck($column)
    {
        $results = $this->get();
        return array_map(fn($item) => $item->$column, $results);
    }

    protected function resetQuery()
    {
        $this->query_where = [];
        $this->query_limit = null;
        $this->query_offset = null;
        $this->query_order = null;
    }

    // ✅ Get First Record
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

    // ✅ Get First Record or Fail
    public function firstOrFail()
    {
        $record = $this->first();
        if (!$record) {
            throw new \Exception("Record not found");
        }
        return $record;
    }
    // ✅ paginate
    public function paginate($perPage = 15, $page = 1): array
    {
        $total = $this->count();
        $offset = ($page - 1) * $perPage;
        $this->limit($perPage)->offset($offset);
        $data = $this->get();
        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    // ✅ Save (Insert or Update)
    public function save()
    {
        $db = new Database();

        if (isset($this->attributes['id'])) {
            return $db->update(static::$table, $this->attributes, ['id' => $this->attributes['id']]);
        } else {
            $insertId = $db->insert(static::$table, $this->attributes);
            $this->attributes['id'] = $insertId;
            return $insertId;
        }
    }

    // ✅ Delete Record
    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            throw new \Exception("No ID set for deletion");
        }

        $db = new Database();
        return $db->delete(static::$table, ['id' => $this->attributes['id']]);
    }

    // ✅ Dynamic Set & Get
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        // Check if relation is already loaded
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        // Check if a method exists for the relation
        if (method_exists($this, $key)) {
            $relation = $this->$key();
            if ($relation instanceof BelongsTo) {
                $result = $relation->getResults();
                $this->relations[$key] = $result;
                return $result;
            }
        }

        return $this->attributes[$key] ?? null;
    }

    protected function eagerLoadRelations(array $models)
    {
        foreach ($this->query_with as $relationName) {
            if (method_exists($this, $relationName)) {
                $relation = (new static)->$relationName();

                if ($relation instanceof BelongsTo) {
                    $relation->addEagerConstraints($models, $relationName);
                }
            } else {
                throw new \RuntimeException("Relationship [{$relationName}] not found on model [" . static::class . "].");
            }
        }
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function setRelation($name, $value)
    {
        $this->relations[$name] = $value;
    }

    /**
     * Specify data which should be serialized to JSON.
     */
    public function jsonSerialize(): mixed
    {
        return $this->attributes;
    }

    /**
     * Basic Many-to-Many Relationship Mock/Helper
     */
    public function belongsToMany($relatedModel, $pivotTable, $foreignKey, $relatedKey)
    {
        return new RelationProxy($this, $relatedModel, $pivotTable, $foreignKey, $relatedKey);
    }

    public function belongsTo($relatedModel, $foreignKey = null)
    {
        if ($foreignKey === null) {
            // Guessed from the method name or class name?
            // Simple approach: related model name + _id
            $className = (new \ReflectionClass($relatedModel))->getShortName();
            $foreignKey = strtolower($className) . '_id';
        }
        return new BelongsTo($this, $relatedModel, $foreignKey);
    }
}

/**
 * Helper class to proxy relationship methods like sync(), detach(), etc.
 */
class RelationProxy
{
    protected $parent;
    protected $relatedModel;
    protected $pivotTable;
    protected $foreignKey;
    protected $relatedKey;

    public function __construct($parent, $relatedModel, $pivotTable, $foreignKey, $relatedKey)
    {
        $this->parent = $parent;
        $this->relatedModel = $relatedModel;
        $this->pivotTable = $pivotTable;
        $this->foreignKey = $foreignKey;
        $this->relatedKey = $relatedKey;
    }

    public function sync($ids)
    {
        $db = new Database();
        // Detach all first
        $db->delete($this->pivotTable, [$this->foreignKey => $this->parent->id]);

        // Attach new ones
        foreach ($ids as $id) {
            $db->insert($this->pivotTable, [
                $this->foreignKey => $this->parent->id,
                $this->relatedKey => $id
            ]);
        }
    }

    public function detach($ids = null)
    {
        $db = new Database();
        $where = [$this->foreignKey => $this->parent->id];
        if ($ids !== null) {
            // If specific ids provided, delete those pivot rows only
            foreach ((array)$ids as $id) {
                $db->delete($this->pivotTable, [$this->foreignKey => $this->parent->id, $this->relatedKey => $id]);
            }
            return;
        }
        $db->delete($this->pivotTable, $where);
    }

    public function get()
    {
        $db = new Database();
        // Join pivot with related table
        // For simplicity, let's just fetch from related table where ID is in pivot
        $pivotResults = $db->select($this->pivotTable, $this->relatedKey, [$this->foreignKey => $this->parent->id]);
        $ids = array_column($pivotResults, $this->relatedKey);

        if (empty($ids)) return [];

        $relatedTable = $this->relatedModel::$table; // This assumes static::$table is accessible
        // We'd need something like Role::whereIn('id', $ids)->get()
        // But for now, let's just use what we have or a simple select
        $results = [];
        foreach ($ids as $id) {
            $results[] = ($this->relatedModel)::find($id);
        }
        return array_filter($results);
    }

    public function pluck($column)
    {
        $db = new Database();
        // This is tricky because we need values from the related table
        // RoleController uses $role->permissions()->pluck('permission_key')
        // In this specific app, permissions are often just strings in the pivot or a permissions table
        
        // Let's assume we want to pluck from the related table
        $items = $this->get();
        return array_map(fn($item) => $item->$column, $items);
    }

    public function count()
    {
        $db = new Database();
        $results = $db->select($this->pivotTable, "COUNT(*) as total", [$this->foreignKey => $this->parent->id]);
        return (int)($results[0]['total'] ?? 0);
    }
}

class BelongsTo
{
    protected $parent;
    protected $relatedModel;
    protected $foreignKey;

    public function __construct($parent, $relatedModel, $foreignKey)
    {
        $this->parent = $parent;
        $this->relatedModel = $relatedModel;
        $this->foreignKey = $foreignKey;
    }

    public function getResults()
    {
        $foreignKeyValue = $this->parent->{$this->foreignKey};
        if (!$foreignKeyValue) return null;

        return ($this->relatedModel)::find($foreignKeyValue);
    }

    public function addEagerConstraints(array $models, $relationName)
    {
        $keys = array_filter(array_map(fn($m) => $m->{$this->foreignKey}, $models));
        if (empty($keys)) return;

        $relatedResults = ($this->relatedModel)::where('id', 'IN', array_unique($keys))->get();
        $relatedMap = [];
        foreach ($relatedResults as $related) {
            $relatedMap[$related->id] = $related;
        }

        foreach ($models as $model) {
            $foreignKeyValue = $model->{$this->foreignKey};
            if (isset($relatedMap[$foreignKeyValue])) {
                $model->setRelation($relationName, $relatedMap[$foreignKeyValue]);
            }
        }
    }
}
