<?php

namespace App\Controllers;

class Controller
{
    /**
     * Return a JSON response
     */
    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Validate request data
     */
    protected function validate($data, $rules)
    {
        // Add validation logic here
        return true;
    }

    /**
     * Check if user is authenticated
     */
    protected function requireAuth()
    {
        // Add authentication check here
    }
}
