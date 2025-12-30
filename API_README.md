# 🚀 PHP OOP Framework - API System Documentation

## Overview
Your PHP OOP framework now includes a complete API system that follows RESTful principles and provides a Laravel-like experience for building APIs.

## 🏗️ Architecture

### Core Components
- **ApiResponse**: Handles JSON responses with consistent formatting
- **ApiRequest**: Extends Request class with JSON parsing and API-specific methods
- **ApiController**: Base controller for all API controllers
- **ApiAuthMiddleware**: Optional authentication middleware

## 📡 Available API Endpoints

### Health Check
```
GET /api/health
```
Returns API status and version information.

### Donors API
```
GET    /api/donors              # Get all donors
GET    /api/donors/{id}         # Get donor by ID
POST   /api/donors              # Create new donor
PUT    /api/donors/{id}         # Update donor
DELETE /api/donors/{id}         # Delete donor
GET    /api/donors/search       # Search donors
```

### API Documentation
```
GET /api/docs
```
Returns complete API documentation and examples.

## 🔧 Creating New API Controllers

### Using Artisan Command
```bash
php artisan make:apicontroller UserApiController
```

This will create a complete API controller with CRUD operations.

### Manual Creation
```php
<?php

namespace App\Controllers\Api;

use App\Controllers\ApiController;

class UserApiController extends ApiController
{
    public function index()
    {
        // Get all users
        return $this->success($users, 'Users retrieved successfully');
    }
    
    public function store()
    {
        // Create user
        $validatedData = $this->request->validateApi([
            'name' => 'required|max:255',
            'email' => 'required|email'
        ]);
        
        // Process data...
        return $this->success($user, 'User created successfully', 201);
    }
}
```

## 📝 Adding API Routes

Add your API routes in `routes/api.php`:

```php
<?php

use App\Services\Route;

// Users API
Route::get('/api/users', ['Api\UserApiController', 'index']);
Route::post('/api/users', ['Api\UserApiController', 'store']);
Route::get('/api/users/{id}', ['Api\UserApiController', 'show']);
Route::put('/api/users/{id}', ['Api\UserApiController', 'update']);
Route::delete('/api/users/{id}', ['Api\UserApiController', 'destroy']);
```

## 🎯 Response Format

### Success Response
```json
{
    "success": true,
    "message": "Success message",
    "data": {
        // Your data here
    },
    "timestamp": "2025-08-30 15:00:00"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    },
    "timestamp": "2025-08-30 15:00:00"
}
```

### Paginated Response
```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [
        // Your data here
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 100,
        "last_page": 10,
        "from": 1,
        "to": 10
    },
    "timestamp": "2025-08-30 15:00:00"
}
```

## 🔐 Authentication (Optional)

### Using API Key Middleware
```php
// In your controller
public function __construct()
{
    // Apply middleware to specific methods
    $this->middleware('api.auth', ['only' => ['store', 'update', 'destroy']]);
}
```

### API Key Header
```
X-API-Key: your-api-key-here
```

## 📊 Testing Your API

### 1. API Testing Dashboard
Visit `/api-test` to access the interactive API testing interface.

### 2. Using cURL
```bash
# Health check
curl http://localhost:8000/api/health

# Get all donors
curl http://localhost:8000/api/donors

# Create donor
curl -X POST http://localhost:8000/api/donors \
  -H "Content-Type: application/json" \
  -d '{
    "donor_name": "John Doe",
    "email": "john@example.com",
    "phone_number": "1234567890",
    "address": "123 Main St",
    "status": 1
  }'
```

### 3. Using JavaScript/Fetch
```javascript
// Get donors
fetch('/api/donors')
  .then(response => response.json())
  .then(data => console.log(data));

// Create donor
fetch('/api/donors', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    donor_name: 'John Doe',
    email: 'john@example.com',
    phone_number: '1234567890',
    address: '123 Main St',
    status: 1
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 🛠️ Validation Rules

### Available Validation Rules
- `required` - Field is required
- `email` - Must be valid email
- `max:255` - Maximum length
- `min:3` - Minimum length
- `numeric` - Must be numeric
- `integer` - Must be integer

### Example
```php
$validatedData = $this->request->validateApi([
    'name' => 'required|max:255',
    'email' => 'required|email',
    'age' => 'required|integer|min:18'
]);
```

## 🌐 CORS Support

The API automatically includes CORS headers:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With`

## 📚 Console Commands

### Available Commands
```bash
php artisan make:controller ControllerName      # Create web controller
php artisan make:model ModelName               # Create model
php artisan make:apicontroller ApiController   # Create API controller
```

## 🚀 Best Practices

1. **Consistent Response Format**: Always use the provided response methods
2. **Error Handling**: Wrap operations in try-catch blocks
3. **Validation**: Always validate input data
4. **HTTP Status Codes**: Use appropriate status codes (200, 201, 400, 404, 500)
5. **Documentation**: Keep API documentation updated

## 🔍 Troubleshooting

### Common Issues
1. **Route not found**: Check if route is properly defined in `routes/api.php`
2. **Method not allowed**: Ensure HTTP method matches route definition
3. **Validation errors**: Check validation rules and input data
4. **Database errors**: Verify database connection and table structure

### Debug Mode
Enable debug mode in `.env`:
```
APP_DEBUG=true
```

## 📈 Next Steps

1. **Add more models** and API controllers
2. **Implement JWT authentication** for secure APIs
3. **Add rate limiting** to prevent abuse
4. **Create API versioning** system
5. **Add comprehensive logging** and monitoring
6. **Implement caching** for better performance

---

## 🎉 Congratulations!

Your PHP OOP framework now has a complete, professional-grade API system that rivals Laravel's API capabilities. You can build robust REST APIs, mobile app backends, and integrate with any frontend framework.

**Happy Coding! 🚀**
