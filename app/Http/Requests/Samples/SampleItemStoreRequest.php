<?php

namespace App\Http\Requests\Samples;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Varchar;
use App\Rules\Text;
use App\Rules\DateTime;
use App\Rules\Image;
use App\Rules\Birthdate;
use App\Rules\TelephoneNumber;
use App\Rules\MobileNumber;
use App\Rules\HexColor;
use App\Rules\PostalCode;
use App\Rules\Integer;
use App\Rules\BinaryImage;

use Validator;

class SampleItemStoreRequest extends FormRequest
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
        $id = $this->route('id');

        $rules = [
            'name' => ['required', new Varchar],
            'description' => ['required', new Text],
            'user' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'data' => 'required',
            'data.*' => 'required|exists:users,id',
            'date' => ['required', new DateTime],
            'status' => 'required',
            'order_column' => ['required', new Integer],
            'dates' => 'required',
            'dates.*' => ['required', new DateTime],
            'birthday' => ['required', new Birthdate],
            'color' => ['required', new HexColor],
            'telephone_number' => ['required', new TelephoneNumber],
            'mobile_number' => ['required', new MobileNumber],
            'zip_code' => ['required', new PostalCode],
        ];

        if (! $id) {
            $rules = array_merge($rules, [
                'images' => 'required',
                'images.*' => ['required', new Image],
                'file_path' => ['required', new Image],
                'files.*' => ['exists:file_records,id']
            ]);
        } else {
            $rules = array_merge($rules, [
                'images' => 'nullable',
                'images.*' => [new Image],
                'file_path' => ['nullable', new Image],
                'files.*' => ['exists:file_records,id'],
                // 'files.*' => [new BinaryImage],
                'media_files.*' => ['exists:file_records,id'],
            ]);
        }

        return $rules;
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'file_path' => 'image',
            'images.*' => 'images',
            'dates.*' => 'dates',
        ];
    }
}
