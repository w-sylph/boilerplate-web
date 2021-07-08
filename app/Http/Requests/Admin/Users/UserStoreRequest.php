<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Name;
use App\Rules\Email;
use App\Rules\Gender;
use App\Rules\Username;
use App\Rules\Birthdate;
use App\Rules\Image;
use App\Rules\TelephoneNumber;
use App\Rules\CountryCode;
use App\Rules\MobileNumber;

class UserStoreRequest extends FormRequest
{
    protected $id;

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
        $this->id = $this->route('id');

        return [
            'first_name' => ['required', new Name],
            'last_name' => ['required', new Name],
            'username' => ['nullable', new Username('users', $this->id, true)],
            'email' => ['required', new Email('users', $this->id)],
            'file_path' => ['nullable', new Image],
            'birthday' => ['nullable', new Birthdate],
            'gender' => ['nullable', new Gender],
            'telephone_number' => ['nullable', new TelephoneNumber, 'unique:users,telephone_number,' . $this->id],
            'mobile_number_country_code' => ['nullable', new CountryCode],
            'mobile_number' => ['nullable', new MobileNumber, 'unique:users,mobile_number,' . $this->id],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'file_path' => 'avatar',
        ];
    }
}
