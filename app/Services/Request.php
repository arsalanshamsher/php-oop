<?php

namespace App\Services;

class Request
{
    protected $data;
    protected $files;

    public function __construct()
    {
        $this->data = $_REQUEST;
        $this->files = $_FILES;
    }

    public function all()
    {
        return $this->data;
    }

    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function hasFile($key)
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function file($key)
    {
        return $this->hasFile($key) ? $this->files[$key] : null;
    }

    public function validate(array $rules)
    {
        $errors = [];
        $oldInput = [];

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $inputValue = $this->input($field);
            $oldInput[$field] = $inputValue;

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
                }
            }
        }

        if (!empty($errors)) {
            \App\Services\Session::flash('errors', $errors);
            \App\Services\Session::flash('old', $oldInput);
        
            session_write_close(); // ✅ Ensure session properly saved before redirect
        
            header("Location: " . $_SERVER['HTTP_REFERER']);
            return; // ✅ exit() ki jagah return try karein
        }
        
        

        return $this->all();
    }
}
