<?php

namespace App\Rules;

use App\Extenders\BaseRule as Rule;
use Carbon\Carbon;

class DateTime extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'date|date_format:Y-m-d H:i:s';
        $format = Carbon::parse('today 6pm')->format('Y-m-d H:i:s');
        $this->message = 'The :attribute is not a valid date and time, format must be (' . $format . ').';
    }
}
