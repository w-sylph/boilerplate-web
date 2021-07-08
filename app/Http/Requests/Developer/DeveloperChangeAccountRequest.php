<?php

namespace App\Http\Requests\Developer;

use Illuminate\Foundation\Http\FormRequest;

class DeveloperChangeAccountRequest extends FormRequest
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
        $rules = [
            'guard' => 'required|in:admin,web',
        ];

        switch ($this->input('guard')) {
            case 'admin':
                $rules = array_merge($rules, [
                    'user' => 'required|exists:admins,id',
                ]);
                break;
            case 'web';
                $rules = array_merge($rules, [
                    'user' => 'required|exists:users,id',
                ]);
                break;
            default:
                $rules = array_merge($rules, [
                    'user' => 'required',
                ]);
                break;
        }

        return $rules;
    }
}
