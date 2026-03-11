<?php

use App\Controllers\Dashboard\DashboardController;
use App\Controllers\Dashboard\RoleController;
use App\Core\Routing\Route;
use App\Controllers\HomeController;


Route::get('/', function () {
    return view('welcome');
})->name('home');
// Auth Routes
Route::get('/login', [\App\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Controllers\AuthController::class, 'login'])->name('login.authenticate');
Route::get('/register', [\App\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Controllers\AuthController::class, 'register'])->name('register.store');
Route::get('/logout', [\App\Controllers\AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [\App\Controllers\AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [\App\Controllers\AuthController::class, 'resetPassword'])->name('password.update');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Role Routes
    Route::get('dashboard/roles',[RoleController::class,'index'])->name('dashboard.roles.index');
    // User Routes
    Route::get('dashboard/users',[UserController::class,'index'])->name('dashboard.users.index');
});
