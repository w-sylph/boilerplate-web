<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Date extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'date|date_format:Y-m-d';
        $format = now()->format('Y-m-d');
        $this->message = 'The :attribute is not a valid date, format must be (' . $format . ').';
    }
}
