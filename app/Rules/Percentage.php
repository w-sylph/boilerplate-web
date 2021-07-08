<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Percentage extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'numeric|between:1,99';
        $this->message = 'The :attribute must be between 1 to 99 percent.';
    }
}
