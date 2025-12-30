<?php

namespace App\Middlewhere;

use App\Services\ApiResponse;

class ApiAuthMiddleware
{
    /**
     * Handle the incoming request
     */
    public function handle($request, $next)
    {
        // Check if API key is provided
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return ApiResponse::unauthorized('API key is required');
        }
        
        // Simple API key validation (you can enhance this)
        $validApiKeys = [
            'test-api-key-123',
            'production-api-key-456'
        ];
        
        if (!in_array($apiKey, $validApiKeys)) {
            return ApiResponse::unauthorized('Invalid API key');
        }
        
        // Continue to the next middleware or controller
        return $next($request);
    }
}
