<?php

namespace App\Services;

use Exception;

class MiniBlade
{
    protected $viewPath;
    protected $layout = null;
    protected $sections = [];
    protected $currentSection = null;
    protected $data = [];

    public function __construct($viewPath)
    {
        $this->viewPath = rtrim($viewPath, '/') . '/';
    }

    public function render($view, $data = [])
    {
        $this->data = $data;
        extract($data);

        // Buffer the output of the view
        ob_start();
        include $this->viewPath . $view . '.php';
        $content = ob_get_clean();

        // If a layout was extended, render the layout
        if ($this->layout) {
            // Check if there is any content outside of sections (optional, usually ignored or treated as 'content')
            // For this simple implementation, we rely on sections for dynamic content in layouts.
            
            // We need to render the layout. The layout will call 'yield' to get section content.
            ob_start();
            include $this->viewPath . $this->layout . '.php';
            return ob_get_clean();
        }

        return $content;
    }

    public function extend($layout)
    {
        $this->layout = $layout;
    }

    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection()
    {
        if ($this->currentSection === null) {
            throw new Exception("Cannot end a section without starting one.");
        }

        $content = ob_get_clean();
        $this->sections[$this->currentSection] = $content;
        $this->currentSection = null;
    }

    public function yield($name, $default = '')
    {
        echo $this->sections[$name] ?? $default;
    }
}