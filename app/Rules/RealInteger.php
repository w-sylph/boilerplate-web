<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class RealInteger extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'integer';
        $this->message = 'The :attribute must be a valid number.';
    }
}
