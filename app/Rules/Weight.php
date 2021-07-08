<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Weight extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'numeric|min:0';
    }
}
