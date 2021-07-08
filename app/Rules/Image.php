<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Image extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'mimes:jpeg,jpg,png,gif,bmp|max:10000';
        $this->message = 'The :attribute must be a valid image with maximum size of 10mb.';
    }
}
