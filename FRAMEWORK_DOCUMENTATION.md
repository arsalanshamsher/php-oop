# 🚀 PHP OOP Framework - Complete Documentation

## 📋 Table of Contents
1. [Introduction](#introduction)
2. [Installation & Setup](#installation--setup)
3. [Project Structure](#project-structure)
4. [Core Concepts](#core-concepts)
5. [Routing System](#routing-system)
6. [Controllers](#controllers)
7. [Models & Database](#models--database)
8. [Views & Templates](#views--templates)
9. [API System](#api-system)
10. [Console Commands](#console-commands)
11. [Security Features](#security-features)
12. [Configuration](#configuration)
13. [Best Practices](#best-practices)
14. [Examples & Tutorials](#examples--tutorials)

---

## 🎯 Introduction

The PHP OOP Framework is a modern, lightweight web application framework built with Object-Oriented Programming principles. It provides a clean, Laravel-inspired architecture that's easy to learn and powerful to use.

### ✨ Key Features
- **MVC Architecture** - Clean separation of concerns
- **Smart Routing** - RESTful routes with middleware support
- **Database ORM** - Powerful object-relational mapping
- **RESTful API** - Built-in API system with JSON responses
- **Console Commands** - Artisan-like development tools
- **Security First** - Built-in security features
- **Lightweight** - Minimal dependencies, maximum performance

---

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer (for autoloading)

### Step 1: Download Framework
```bash
# Clone the repository
git clone https://github.com/your-repo/php-oop-framework.git
cd php-oop-framework

# Or download and extract manually
```

### Step 2: Configure Environment
Create a `.env` file in the root directory:
```env
APP_NAME=MyApp
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Database Setup
```sql
-- Create database
CREATE DATABASE your_database;

-- Import schema (if available)
-- mysql -u root -p your_database < database/schema.sql
```

### Step 4: Start Development Server
```bash
# Using PHP built-in server
php -S localhost:8000

# Or configure your web server (Apache/Nginx)
```

---

## 📁 Project Structure

```
php-oop-framework/
├── app/                          # Application core
│   ├── Controllers/             # Application controllers
│   │   └── Api/                # API controllers
│   ├── Models/                  # Database models
│   ├── Services/                # Core services
│   ├── Middlewhere/            # Middleware classes
│   ├── Config/                  # Configuration files
│   ├── Console/                 # Console commands
│   ├── Exceptions/              # Custom exceptions
│   ├── Helpers.php              # Helper functions
│   └── helper.php               # Additional helpers
├── views/                       # View templates
│   ├── admin/                   # Admin panel views
│   ├── landing/                 # Landing page views
│   └── layouts/                 # Layout templates
├── routes/                      # Route definitions
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
├── public/                      # Public assets
├── vendor/                      # Composer dependencies
├── .env                         # Environment configuration
├── index.php                    # Application entry point
└── artisan                      # Console command runner
```

---

## 🧠 Core Concepts

### MVC Architecture
The framework follows the Model-View-Controller pattern:

- **Model**: Handles data and business logic
- **View**: Displays data to users
- **Controller**: Processes requests and coordinates between Model and View

### Request Flow
1. Request comes to `index.php`
2. Routes are loaded and matched
3. Controller method is called
4. Model processes data
5. View renders response
6. Response sent to browser

---

## 🛣️ Routing System

### Basic Routes
```php
// routes/web.php
use App\Services\Route;

// Simple GET route
Route::get('/', function () {
    return view('welcome');
});

// Controller route
Route::get('/users', ['UserController', 'index']);

// Named route
Route::get('/profile', ['UserController', 'profile'])->name('user.profile');
```

### HTTP Methods
```php
// GET route
Route::get('/users', ['UserController', 'index']);

// POST route
Route::post('/users', ['UserController', 'store']);

// PUT route
Route::put('/users/{id}', ['UserController', 'update']);

// DELETE route
Route::delete('/users/{id}', ['UserController', 'destroy']);

// PATCH route
Route::patch('/users/{id}', ['UserController', 'update']);
```

### Route Parameters
```php
// Single parameter
Route::get('/users/{id}', ['UserController', 'show']);

// Multiple parameters
Route::get('/posts/{category}/{id}', ['PostController', 'show']);

// Optional parameters
Route::get('/search/{query?}', ['SearchController', 'index']);
```

### Named Routes
```php
// Define named route
Route::get('/profile', ['UserController', 'profile'])->name('user.profile');

// Generate URL from route name
$url = route('user.profile'); // Returns: /profile

// With parameters
$url = route('user.show', ['id' => 5]); // Returns: /users/5
```

---

## 🎮 Controllers

### Creating Controllers
```bash
# Using artisan command
php artisan make:controller UserController

# Manual creation
# Create file: app/Controllers/UserController.php
```

### Basic Controller Structure
```php
<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function index()
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', ['user' => $user]);
    }

    public function store()
    {
        // Handle form submission
        $user = new User();
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->save();

        return redirect()->route('users.index');
    }
}
```

### Controller Methods
- **index()** - Display list of resources
- **show($id)** - Display specific resource
- **create()** - Show creation form
- **store()** - Save new resource
- **edit($id)** - Show edit form
- **update($id)** - Update resource
- **destroy($id)** - Delete resource

---

## 🗄️ Models & Database

### Creating Models
```bash
# Using artisan command
php artisan make:model User

# Manual creation
# Create file: app/Models/User.php
```

### Basic Model Structure
```php
<?php

namespace App\Models;

class User extends Model
{
    protected static $table = 'users';
    
    // Define fillable fields
    protected $fillable = ['name', 'email', 'password'];
    
    // Define relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

### Database Operations
```php
// Create new record
$user = new User();
$user->name = 'John Doe';
$user->email = 'john@example.com';
$user->save();

// Find record by ID
$user = User::find(1);

// Find record by condition
$user = User::where('email', 'john@example.com')->first();

// Update record
$user->name = 'Jane Doe';
$user->save();

// Delete record
$user->delete();

// Get all records
$users = User::all();

// Pagination
$users = User::paginate(10);
```

### Query Builder
```php
// Basic where clause
$users = User::where('status', 'active')->get();

// Multiple conditions
$users = User::where('status', 'active')
             ->where('age', '>', 18)
             ->get();

// Order by
$users = User::orderBy('name', 'asc')->get();

// Limit results
$users = User::limit(10)->get();

// Select specific columns
$users = User::select('id', 'name', 'email')->get();
```

---

## 🎨 Views & Templates

### Creating Views
Views are PHP files located in the `views/` directory:

```php
<!-- views/users/index.php -->
<?= include_file('layouts/header.php') ?>

<div class="container">
    <h1>Users</h1>
    
    <?php foreach ($users as $user): ?>
        <div class="user-card">
            <h3><?= htmlspecialchars($user->name) ?></h3>
            <p><?= htmlspecialchars($user->email) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?= include_file('layouts/footer.php') ?>
```

### Layouts
```php
<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My App' ?></title>
    <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
</head>
<body>
    <nav>
        <!-- Navigation content -->
    </nav>
    <main>
```

### Helper Functions
```php
// Asset URL
$cssUrl = asset('/css/style.css');

// Route URL
$profileUrl = route('user.profile');

// Old input (for forms)
$oldName = old('name', '');

// CSRF token
$token = csrf_token();
```

---

## 🔌 API System

### API Controllers
```php
<?php

namespace App\Controllers\Api;

use App\Controllers\ApiController;
use App\Models\User;

class UserApiController extends ApiController
{
    public function index()
    {
        $users = User::all();
        return $this->success($users, 'Users retrieved successfully');
    }

    public function store()
    {
        $validatedData = $this->request->validateApi([
            'name' => 'required|max:255',
            'email' => 'required|email'
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save();

        return $this->success($user, 'User created successfully', 201);
    }
}
```

### API Routes
```php
// routes/api.php
Route::get('/api/users', ['Api\UserApiController', 'index']);
Route::post('/api/users', ['Api\UserApiController', 'store']);
Route::get('/api/users/{id}', ['Api\UserApiController', 'show']);
Route::put('/api/users/{id}', ['Api\UserApiController', 'update']);
Route::delete('/api/users/{id}', ['Api\UserApiController', 'destroy']);
```

### API Response Format
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

### Testing API
Visit `/api-test` for interactive API testing interface.

---

## ⚡ Console Commands

### Available Commands
```bash
# Create controller
php artisan make:controller UserController

# Create model
php artisan make:model User

# Create API controller
php artisan make:apicontroller UserApiController
```

### Custom Commands
Create custom commands in `app/Console/Commands/`:

```php
<?php

namespace App\Console\Commands;

class MakeMigration
{
    public function execute($params)
    {
        $tableName = $params[0] ?? 'table';
        
        // Generate migration file
        $content = $this->generateMigrationContent($tableName);
        $filename = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
        
        file_put_contents('database/migrations/' . $filename, $content);
        
        echo "✅ Migration created: $filename\n";
    }
}
```

---

## 🔒 Security Features

### Input Validation
```php
// In controller
$validatedData = $this->request->validate([
    'name' => 'required|max:255',
    'email' => 'required|email',
    'age' => 'required|integer|min:18'
]);
```

### CSRF Protection
```php
// In forms
<input type="hidden" name="_token" value="<?= csrf_token() ?>">

// Verify token in controller
if (!verify_csrf_token($_POST['_token'])) {
    die('CSRF token mismatch');
}
```

### SQL Injection Prevention
The framework uses prepared statements automatically:
```php
// Safe query
$users = User::where('status', $status)->get();

// Framework automatically escapes values
```

### XSS Protection
```php
// Always escape output
echo htmlspecialchars($user->name);

// Or use the e() helper
echo e($user->name);
```

---

## ⚙️ Configuration

### Environment Variables
```env
# Application
APP_NAME=MyApp
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=

# Mail (if implemented)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Database Configuration
```php
// app/Config/database.php
class Database
{
    public function __construct()
    {
        $host = env('DB_HOST');
        $dbname = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        
        // PDO connection setup
    }
}
```

---

## 📚 Best Practices

### Code Organization
1. **Controllers**: Keep them thin, move business logic to models
2. **Models**: Handle data relationships and business rules
3. **Views**: Keep presentation logic separate
4. **Routes**: Group related routes together

### Naming Conventions
- **Controllers**: `UserController`, `PostController`
- **Models**: `User`, `Post`
- **Views**: `users/index.php`, `posts/show.php`
- **Routes**: `users.index`, `posts.show`

### Security Guidelines
1. Always validate input data
2. Use prepared statements for database queries
3. Escape output to prevent XSS
4. Implement CSRF protection
5. Use HTTPS in production

### Performance Tips
1. Use database indexes for frequently queried columns
2. Implement caching for expensive operations
3. Optimize database queries
4. Use lazy loading for relationships

---

## 🎓 Examples & Tutorials

### Building a Blog System

#### 1. Create Models
```bash
php artisan make:model Post
php artisan make:model Category
php artisan make:model Comment
```

#### 2. Create Controllers
```bash
php artisan make:controller PostController
php artisan make:controller CategoryController
```

#### 3. Define Routes
```php
// routes/web.php
Route::get('/posts', ['PostController', 'index']);
Route::get('/posts/{id}', ['PostController', 'show']);
Route::get('/categories', ['CategoryController', 'index']);
```

#### 4. Create Views
```php
<!-- views/posts/index.php -->
<?= include_file('layouts/header.php') ?>

<div class="container">
    <h1>Blog Posts</h1>
    
    <?php foreach ($posts as $post): ?>
        <article class="post">
            <h2><?= e($post->title) ?></h2>
            <p><?= e($post->excerpt) ?></p>
            <a href="/posts/<?= $post->id ?>">Read More</a>
        </article>
    <?php endforeach; ?>
</div>

<?= include_file('layouts/footer.php') ?>
```

### Building a REST API

#### 1. Create API Controller
```bash
php artisan make:apicontroller PostApiController
```

#### 2. Define API Routes
```php
// routes/api.php
Route::get('/api/posts', ['Api\PostApiController', 'index']);
Route::post('/api/posts', ['Api\PostApiController', 'store']);
Route::get('/api/posts/{id}', ['Api\PostApiController', 'show']);
Route::put('/api/posts/{id}', ['Api\PostApiController', 'update']);
Route::delete('/api/posts/{id}', ['Api\PostApiController', 'destroy']);
```

#### 3. Implement API Methods
```php
public function index()
{
    $posts = Post::with('category')->get();
    return $this->success($posts, 'Posts retrieved successfully');
}

public function store()
{
    $validatedData = $this->request->validateApi([
        'title' => 'required|max:255',
        'content' => 'required',
        'category_id' => 'required|integer'
    ]);

    $post = Post::create($validatedData);
    return $this->success($post, 'Post created successfully', 201);
}
```

---

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure your web server (Apache/Nginx)
4. Set up SSL certificate
5. Configure database for production

### Web Server Configuration

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## 🆘 Troubleshooting

### Common Issues

#### 1. Route Not Found
- Check if route is defined in correct file
- Verify route syntax
- Check if controller exists

#### 2. Database Connection Error
- Verify database credentials in `.env`
- Check if database exists
- Ensure MySQL service is running

#### 3. Class Not Found
- Check namespace declarations
- Verify autoloader is working
- Check file paths and names

#### 4. View Not Found
- Verify view file exists
- Check view path in controller
- Ensure correct file extension

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

This will show detailed error messages and stack traces.

---

## 📖 Additional Resources

### Official Documentation
- [API Documentation](http://localhost:8000/api/docs)
- [API Testing Interface](http://localhost:8000/api-test)

### Community
- GitHub Repository
- Issue Tracker
- Discussion Forum

### Learning Path
1. Start with basic routing and controllers
2. Learn about models and database operations
3. Explore the API system
4. Master console commands
5. Implement security features
6. Build real-world applications

---

## 🎉 Conclusion

The PHP OOP Framework provides a solid foundation for building modern web applications. With its clean architecture, powerful features, and comprehensive documentation, you can quickly develop robust and scalable applications.

Remember to:
- Follow best practices
- Keep security in mind
- Write clean, maintainable code
- Test your applications thoroughly
- Contribute to the community

**Happy Coding! 🚀**

---

*This documentation is maintained by the PHP OOP Framework team. For questions and support, please visit our community forums.*
