<?php

namespace App\Services;

class ApiRequest extends Request
{
    protected $jsonData;

    public function __construct()
    {
        parent::__construct();
        $this->parseJsonInput();
    }

    /**
     * Parse JSON input from request body
     */
    protected function parseJsonInput()
    {
        $input = file_get_contents('php://input');
        if ($input && $this->isJson($input)) {
            $this->jsonData = json_decode($input, true);
            if ($this->jsonData) {
                $this->data = array_merge($this->data, $this->jsonData);
            }
        }
    }

    /**
     * Check if string is valid JSON
     */
    protected function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get JSON data
     */
    public function json($key = null, $default = null)
    {
        if ($key === null) {
            return $this->jsonData;
        }
        return $this->jsonData[$key] ?? $default;
    }

    /**
     * Get request method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check if request is GET
     */
    public function isGet()
    {
        return $this->method() === 'GET';
    }

    /**
     * Check if request is POST
     */
    public function isPost()
    {
        return $this->method() === 'POST';
    }

    /**
     * Check if request is PUT
     */
    public function isPut()
    {
        return $this->method() === 'PUT';
    }

    /**
     * Check if request is DELETE
     */
    public function isDelete()
    {
        return $this->method() === 'DELETE';
    }

    /**
     * Get request headers
     */
    public function header($key, $default = null)
    {
        $headers = getallheaders();
        return $headers[$key] ?? $default;
    }

    /**
     * Get authorization header
     */
    public function bearerToken()
    {
        $authHeader = $this->header('Authorization');
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            return substr($authHeader, 7);
        }
        return null;
    }

    /**
     * Validate API request with custom error handling
     */
    public function validateApi(array $rules)
    {
        $errors = [];
        $validatedData = [];

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $inputValue = $this->input($field);
            $validatedData[$field] = $inputValue;

            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && empty($inputValue)) {
                    $errors[$field][] = "$field is required";
                } elseif ($rule === 'email' && !filter_var($inputValue, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "$field must be a valid email";
                } elseif (strpos($rule, 'max:') === 0) {
                    $maxValue = explode(':', $rule)[1];
                    if (strlen($inputValue) > $maxValue) {
                        $errors[$field][] = "$field must not exceed $maxValue characters";
                    }
                } elseif (strpos($rule, 'min:') === 0) {
                    $minValue = explode(':', $rule)[1];
                    if (strlen($inputValue) < $minValue) {
                        $errors[$field][] = "$field must be at least $minValue characters";
                    }
                } elseif ($rule === 'numeric' && !is_numeric($inputValue)) {
                    $errors[$field][] = "$field must be numeric";
                } elseif ($rule === 'integer' && !filter_var($inputValue, FILTER_VALIDATE_INT)) {
                    $errors[$field][] = "$field must be an integer";
                }
            }
        }

        if (!empty($errors)) {
            ApiResponse::validationError($errors);
        }

        return $validatedData;
    }
}
