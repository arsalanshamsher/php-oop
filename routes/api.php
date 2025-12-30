<?php

use App\Services\Route;

// API Routes for Donors
Route::get('/api/donors', ['Api\DonorApiController', 'index'])->name('api.donors.index');
Route::get('/api/donors/{id}', ['Api\DonorApiController', 'show'])->name('api.donors.show');
Route::post('/api/donors', ['Api\DonorApiController', 'store'])->name('api.donors.store');
Route::put('/api/donors/{id}', ['Api\DonorApiController', 'update'])->name('api.donors.update');
Route::delete('/api/donors/{id}', ['Api\DonorApiController', 'destroy'])->name('api.donors.destroy');
Route::get('/api/donors/search', ['Api\DonorApiController', 'search'])->name('api.donors.search');

// API Health Check
Route::get('/api/health', function () {
    return json_encode([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ]);
})->name('api.health');

// API Documentation
Route::get('/api/docs', function () {
    return json_encode([
        'status' => 'success',
        'message' => 'API Documentation',
        'endpoints' => [
            'GET /api/health' => 'Health check endpoint',
            'GET /api/donors' => 'Get all donors',
            'GET /api/donors/{id}' => 'Get donor by ID',
            'POST /api/donors' => 'Create new donor',
            'PUT /api/donors/{id}' => 'Update donor',
            'DELETE /api/donors/{id}' => 'Delete donor',
            'GET /api/donors/search?q={query}&status={status}' => 'Search donors'
        ],
        'example_request' => [
            'POST /api/donors' => [
                'donor_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone_number' => '1234567890',
                'address' => '123 Main St',
                'status' => 1
            ]
        ]
    ]);
})->name('api.docs');
