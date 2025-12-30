<?php

namespace App\Console\Commands;

class MakeApiController
{
    public function execute($params)
    {
        if (empty($params)) {
            echo "❌ Error: Controller name is required\n";
            echo "Usage: php artisan make:apicontroller ControllerName\n";
            return;
        }

        $controllerName = $params[0];
        $modelName = str_replace('ApiController', '', $controllerName);
        $modelName = str_replace('Controller', '', $modelName);
        
        // Convert to singular if it ends with 's'
        if (substr($modelName, -1) === 's') {
            $modelName = substr($modelName, 0, -1);
        }

        $controllerContent = $this->generateControllerContent($controllerName, $modelName);
        $controllerPath = dirname(__DIR__, 3) . '/app/Controllers/Api/' . $controllerName . '.php';
        
        // Create API directory if it doesn't exist
        $apiDir = dirname($controllerPath);
        if (!is_dir($apiDir)) {
            mkdir($apiDir, 0755, true);
        }
        
        if (file_put_contents($controllerPath, $controllerContent)) {
            echo "✅ API Controller Created: app/Controllers/Api/{$controllerName}.php\n";
            echo "📝 Model Name: {$modelName}\n";
            echo "🔗 Don't forget to add routes in routes/api.php\n";
        } else {
            echo "❌ Error: Failed to create controller\n";
        }
    }

    private function generateControllerContent($controllerName, $modelName)
    {
        $modelClass = ucfirst($modelName);
        $tableName = strtolower($modelName) . 's';
        
        return "<?php

namespace App\Controllers\Api;

use App\Controllers\ApiController;
use App\Models\\{$modelClass};

class {$controllerName} extends ApiController
{
    /**
     * Get all {$modelName}s
     */
    public function index()
    {
        try {
            \${$tableName} = {$modelClass}::all();
            return \$this->success(\${$tableName}, '{$modelClass}s retrieved successfully');
        } catch (\Exception \$e) {
            return \$this->error('Failed to retrieve {$modelClass}s: ' . \$e->getMessage(), 500);
        }
    }

    /**
     * Get {$modelName} by ID
     */
    public function show(\$id)
    {
        try {
            \${$modelName} = {$modelClass}::where('id', \$id)->first();
            
            if (!\${$modelName}) {
                return \$this->notFound('{$modelClass} not found');
            }
            
            return \$this->success(\${$modelName}, '{$modelClass} retrieved successfully');
        } catch (\Exception \$e) {
            return \$this->error('Failed to retrieve {$modelClass}: ' . \$e->getMessage(), 500);
        }
    }

    /**
     * Create new {$modelName}
     */
    public function store()
    {
        try {
            // Add your validation rules here
            \$validatedData = \$this->request->validateApi([
                'name' => 'required|max:255',
                // Add more fields as needed
            ]);

            \${$modelName} = new {$modelClass}();
            // Set properties from validated data
            foreach (\$validatedData as \$key => \$value) {
                \${$modelName}->\$key = \$value;
            }

            \${$modelName}->save();

            return \$this->success(\${$modelName}, '{$modelClass} created successfully', 201);
        } catch (\Exception \$e) {
            return \$this->error('Failed to create {$modelClass}: ' . \$e->getMessage(), 500);
        }
    }

    /**
     * Update {$modelName}
     */
    public function update(\$id)
    {
        try {
            \${$modelName} = {$modelClass}::where('id', \$id)->first();
            
            if (!\${$modelName}) {
                return \$this->notFound('{$modelClass} not found');
            }

            // Add your validation rules here
            \$validatedData = \$this->request->validateApi([
                'name' => 'required|max:255',
                // Add more fields as needed
            ]);

            // Update properties from validated data
            foreach (\$validatedData as \$key => \$value) {
                \${$modelName}->\$key = \$value;
            }

            \${$modelName}->save();

            return \$this->success(\${$modelName}, '{$modelClass} updated successfully');
        } catch (\Exception \$e) {
            return \$this->error('Failed to update {$modelClass}: ' . \$e->getMessage(), 500);
        }
    }

    /**
     * Delete {$modelName}
     */
    public function destroy(\$id)
    {
        try {
            \${$modelName} = {$modelClass}::where('id', \$id)->first();
            
            if (!\${$modelName}) {
                return \$this->notFound('{$modelClass} not found');
            }

            \${$modelName}->delete();

            return \$this->success(null, '{$modelClass} deleted successfully');
        } catch (\Exception \$e) {
            return \$this->error('Failed to delete {$modelClass}: ' . \$e->getMessage(), 500);
        }
    }
}
";
    }
}
