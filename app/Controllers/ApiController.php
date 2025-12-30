<?php

namespace App\Controllers;

use App\Services\ApiRequest;
use App\Services\ApiResponse;

abstract class ApiController
{
    protected $request;

    public function __construct()
    {
        $this->request = new ApiRequest();
    }

    /**
     * Send success response
     */
    protected function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return ApiResponse::success($data, $message, $statusCode);
    }

    /**
     * Send error response
     */
    protected function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        return ApiResponse::error($message, $statusCode, $errors);
    }

    /**
     * Send not found response
     */
    protected function notFound($message = 'Resource not found')
    {
        return ApiResponse::notFound($message);
    }

    /**
     * Send unauthorized response
     */
    protected function unauthorized($message = 'Unauthorized')
    {
        return ApiResponse::unauthorized($message);
    }

    /**
     * Send forbidden response
     */
    protected function forbidden($message = 'Forbidden')
    {
        return ApiResponse::forbidden($message);
    }

    /**
     * Send validation error response
     */
    protected function validationError($errors, $message = 'Validation failed')
    {
        return ApiResponse::validationError($errors, $message);
    }

    /**
     * Get paginated response
     */
    protected function paginated($data, $page, $perPage, $total, $message = 'Data retrieved successfully')
    {
        return $this->success([
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $total)
            ]
        ], $message);
    }

    /**
     * Handle OPTIONS request for CORS
     */
    public function options()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
        http_response_code(200);
        exit;
    }
}
