<?php

namespace App\Services;

class ApiResponse
{
    /**
     * Send a successful JSON response
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return self::send([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], $statusCode);
    }

    /**
     * Send an error JSON response
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        return self::send([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ], $statusCode);
    }

    /**
     * Send a not found response
     */
    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    /**
     * Send an unauthorized response
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    /**
     * Send a forbidden response
     */
    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    /**
     * Send a validation error response
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Send the actual response
     */
    private static function send($data, $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
