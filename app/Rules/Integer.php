<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Integer extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'integer|min:0';
    }
}
