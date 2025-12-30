<?php

use App\Services\Route;

Route::register('home', '/');

Route::get('/', function () {
    return view('welcome');
});



