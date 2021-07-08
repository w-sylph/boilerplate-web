<?php

namespace App\Http\Requests\Samples;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Text;

class SampleItemDenyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => ['required', new Text],
        ];
    }
}
