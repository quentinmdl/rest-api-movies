<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class MediaUploader
{
    /**
     * Upload a media file to a specific public folder based on the media type.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $mediaType
     * @return string|null
     */
    public static function upload($media, $mediaType, $currentPath = null)
    {
        // Check if the file is a valid uploaded file
        if (!$media->isValid()) {
            return null;
        }

        try {
            if($currentPath) {
                Storage::disk('public')->delete($currentPath);
                $path = Storage::disk('public')->put($mediaType, $media);
            } else {
                $path = Storage::disk('public')->put($mediaType, $media);
            }
            // Return the file path
            return $path;
        } catch (\Exception $e) {
            // Return false if there is an error during the upload
            return false;
        }
    }
}
