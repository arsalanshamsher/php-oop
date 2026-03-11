# My PHP OOP Framework

A lightweight, modern PHP OOP framework inspired by Laravel. Designed for simplicity and speed.

## Features
- **MVC Architecture**: Clean separation of concerns.
- **Custom Router**: Expressive routing with middleware support.
- **ORM-like Models**: Database interaction made easy.
- **Authentication**: Built-in authentication system.
- **Blade-inspired Views**: Simple yet powerful view engine.

## Installation

### 1. Via Composer (Recommended)
Once registered on Packagist, you can install it using:
```bash
composer create-project your-username/php-framework your-project-name
```

### 2. Manual Cloned Installation
If you have the source code:
1. Clone the repository.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and configure your database.
4. Run migrations: `php artisan migrate`.
5. Start the server: `php -S localhost:8000 -t public`.

## Directory Structure
- `app/`: Application core logic.
  - `Core/`: Framework engine.
  - `Controllers/`: Your controllers.
  - `Models/`: Your database models.
  - `Middleware/`: Custom middlewares.
- `public/`: Publicly accessible files (index.php, CSS, JS).
- `routes/`: Route definitions (`web.php`).
- `views/`: HTML templates.

## Usage

### Defining Routes
In `routes/web.php`:
```php
Route::get('/hello', function() {
    return "Hello World!";
});
```

### Creating Controllers
```php
namespace App\Controllers;

class HelloController extends Controller {
    public function index() {
        return view('welcome');
    }
}
```

## License
MIT
