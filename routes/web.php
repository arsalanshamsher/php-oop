<?php
use App\Services\Route;


Route::get('/',function(){
    return view('welcome');
});

Route::get('/home','HomeController','index')->name('home');
Route::get('/admin/dashboard','AdminController','index')->name('admin-dashboard');
Route::get('/admin/dashboard/create','AdminController','create')->name('admin-dashboard-create');
Route::post('/admin/dashboard/store','AdminController','store')->name('admin-dashboard-store');
Route::get('/admin/dashboard/{id}','AdminController','show')->name('admin-dashboard-show');