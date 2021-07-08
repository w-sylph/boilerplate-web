<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class ImportFile extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'mimes:csv,txt,xls,xlsx|max:2000';
        $this->message = 'The :attribute must be a valid document format (csv, xls, xlsx) and not exceed 2MB.';
    }
}
