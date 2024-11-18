<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileService
{
    private $fileDirectory;

    private $prefix;

    public function getFileDirectory()
    {
        return $this->fileDirectory;
    }

    public function setFileDirectory($fileDirectory)
    {
        $this->fileDirectory = $fileDirectory;
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function uploadFile(UploadedFile $file): string
    {
        $fileName = $this->prefix . '_' .$file->getClientOriginalName();
        $file->move($this->getFileDirectory(), $fileName);

        return $this->getFileDirectory() . $fileName;
    }

    public function deleteFile(string $path): bool
    {
        if (File::exists($path)) {
            return File::delete($path);
        }

        return false;
    }
}