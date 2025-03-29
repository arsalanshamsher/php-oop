<?php

use App\Services\Route;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Laravel-style controller route mapping
Route::get('/home', ['HomeController', 'index'])->name('home');
Route::get('/admin/dashboard', ['AdminController', 'index'])->name('admin-dashboard');
Route::get('/admin/donors', ['AdminController', 'index'])->name('admin-donor-list');
Route::get('/admin/donor/create', ['AdminController', 'create'])->name('admin-donor-create');
Route::post('/admin/dashboard/store', ['AdminController', 'store'])->name('admin-dashboard-store');
Route::get('/admin/dashboard/{id}', ['AdminController', 'show'])->name('admin-dashboard-show');
