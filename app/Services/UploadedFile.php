<?php

namespace App\Services;

class UploadedFile
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getClientOriginalName()
    {
        return $this->file['name'];
    }

    public function getSize()
    {
        return $this->file['size'];
    }

    public function getMimeType()
    {
        return $this->file['type'];
    }

    public function move($destination, $newName = null)
    {
        $newName = $newName ?? $this->file['name'];
        $targetPath = rtrim($destination, '/') . '/' . $newName;

        if (move_uploaded_file($this->file['tmp_name'], $targetPath)) {
            return $targetPath;
        }

        return false;
    }
}
