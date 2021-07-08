<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class File extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'mimes:pdf,docx,doc,odt,ods|max: 2000';
        $this->message = 'The :attribute must be a valid document format (pdf, docx and or doc) and not exceed 2MB.';
    }
}
