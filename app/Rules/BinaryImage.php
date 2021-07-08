<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;
use App\Actions\Files\BinaryImageUploader;

class BinaryImage extends Rule
{
    protected $validateUrl = false;
    protected $action;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validateUrl = false)
    {
        $this->validateUrl = $validateUrl;
        $this->action = new BinaryImageUploader;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $action = $this->action;
        $action->setAttributes($value);
        $validFormat = $this->validateFormat($value);
        $validMime = $this->validateExtension($action->getExtension());

        $imageSize = $action->getSize() / 1024; // convert to kb
        $validSize = $this->validateSize($action->getSize() / 1024);
        $validUrl = false;

        if ($this->validateUrl) {
            if (!$validFormat || !$validMime) {
                $validUrl = $this->validateUrl($value);
            }
        }

        return ($validFormat && $validMime && $validSize) || $validUrl;
    }

    /* Check if binary image is below maximum file upload size */
    protected function validateSize($fileSize) {
        $maxFileSize = 2048; // 2mb
        return $fileSize < $maxFileSize;
    }

    /* Check if binary image has valid extension */
    protected function validateExtension($extension) {
        $validExtensions = ['jpeg', 'png', 'jpg', 'bmp', 'gif'];
        return in_array($extension, $validExtensions);
    }

    /* Check if string is a valid binary image */
    protected function validateFormat($value) {
        $result = false;
        $image = explode(',', $value);

        if (isset($image[1])) {
            $image = imagecreatefromstring(base64_decode($image[1]));

            if ($image) {
                $result = true;
            }
        }

        return $result;
    }

    /* Check if string is a valid url */
    protected function validateUrl($value) {
        return preg_match('/^(https?|ftp):\/\/.*(jpeg|png|gif|bmp)/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'The :attribute must be a valid image and must be less than 2mb.';
    }
}
