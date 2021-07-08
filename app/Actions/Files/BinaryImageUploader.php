<?php

namespace App\Actions\Files;

use Intervention\Image\Facades\Image;
use App\Actions\Files\FileUploader;

class BinaryImageUploader extends FileUploader
{
    /**
     * Check if file should be compressed
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     * @return boolean
     */
    protected function shouldCompress($file) {
        return true;
    }

    /**
     * Get image attribute such as filename, size, mime and extension
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     */
    public function setAttributes($file) {
        $this->mime = $this->getBinaryMime($file);

        /* Set extension based on binary mime */
        $this->extension = str_replace('image/', '', $this->mime);

        $this->size = $this->getBinarySize($file);
    }

    /**
     * Get encoded image
     * @param  @param  Illuminate\Http\UploadedFile $file uploaded file
     * @return Intervention\Image\Facades\Image intervention image
     */
    public function getImage($file) {
        $extension = $this->extension;
        return $this->getBinaryImage($file, $extension);
    }

    /* Convert base64 to image */
    public function getBinaryImage($value, $extension = null) {
        $base64 = substr($value, strpos($value, ',') + 1);
        $image = Image::make($base64);

        if ($extension) {
            $image = $image->encode($extension);
        }

        return $image;
    }

    /* Get mime of base64 image */
    public function getBinaryMime($value) {
        $pos = strpos($value, ';');
        $result = explode(':', substr($value, 0, $pos));

        if (isset($result[1])) {
            $result = strtolower($result[1]);
        } else {
            $result = '';
        }

        $this->mime = $result;

        return $result;
    }

    /**
     * Get base64 image size
     * @param  string $value base64 image
     * @return int  base64 image size
     */
    public function getBinarySize($value) {
        $fileSize = (int) (strlen(rtrim($value, '=')) * 3 / 4);
        return $fileSize;
    }
}