<?php

namespace App\Core\View;

class View
{
    protected $sections = [];
    protected $currentSection;
    protected $layout;
    protected $data = [];

    public function render($file, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        extract($this->data);

        ob_start();
        require $file;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutFile = APP_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . str_replace(['/', '.'], DIRECTORY_SEPARATOR, $this->layout) . '.php';
            
            // Add the main content to a special section named 'content' if not already set
            if (!isset($this->sections['content'])) {
                $this->sections['content'] = $content;
            }

            return $this->renderLayout($layoutFile);
        }

        return $content;
    }

    protected function renderLayout($file)
    {
        extract($this->data);
        ob_start();
        require $file;
        return ob_get_clean();
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
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }

    public function renderSection($name)
    {
        return $this->sections[$name] ?? '';
    }

    public function yield($name)
    {
        echo $this->renderSection($name);
    }
}
