<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Birthdate extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = [new Date, 'before:13 years ago', 'after: 129 years ago'];
    }
}
