<?php

namespace App\Extenders;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class BaseRule implements Rule
{
    protected $rules;
    protected $message;
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strpos($attribute, '.') !== false) {
            $attribute = explode('.', $attribute)[0];
        }

        $validator = Validator::make([$attribute => $value], [
            $attribute => $this->rules,
        ]);

        if ($validator->fails()) {
            $this->errorMessage = $validator->errors()->first();
        }

        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? $this->errorMessage;
    }

    public function setMessage($message) {
        $this->message = $message;
    }
}
