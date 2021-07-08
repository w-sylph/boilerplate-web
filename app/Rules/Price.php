<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;

class Price extends Rule
{
    protected $min = 0;
    protected $max = 0;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setRules();
    }

    protected function setRules() {
        $this->rules = 'numeric|gte:' . $this->min;
    }

    public function setMin($value) {
        $this->min = $value;
        $this->setRules();
    }
}
