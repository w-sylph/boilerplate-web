<?php

namespace App\Http\Requests\Admin\Articles;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Varchar;
use App\Rules\HTMLText;
use App\Rules\DateTime;
use App\Rules\Image;

class ArticleStoreRequest extends FormRequest
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
            'description' => ['required', new HTMLText],
            'published_at' => ['required', new DateTime],
        ];

        if (!$id) {
            $rules = array_merge($rules, [
                'images' => 'required',
                'images.*' => [new Image],
                'file_path' => ['required', new Image],
            ]);
        } else {
            $rules = array_merge($rules, [
                'images' => 'nullable',
                'images.*' => [new Image],
                'file_path' => ['nullable', new Image],
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
            'published_at' => 'published date',
            'file_path' => 'image',
        ];
    }
}
