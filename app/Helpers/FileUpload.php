<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUpload
{
    public static function storeFile(UploadedFile $file, $folder = 'uploads')
    {
        // Store the file locally and return the path
        $path = $file->store($folder, 'public'); // Store under 'public' disk

        // Return the URL to the stored file
        return Storage::url($path);
    }

    public static function getFullUrl($filePath)
    {
        // Prepend the domain using Laravel's `url()` helper
        return url(Storage::url($filePath));
    }

}
