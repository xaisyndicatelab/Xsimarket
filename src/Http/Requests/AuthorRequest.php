<?php

namespace Xsimarket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class AuthorRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'slug'        => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'shop_id'     => ['nullable', 'exists:Xsimarket\Database\Models\Shop,id'],
            'image' => ['array'],
            'cover_image' => ['array'],
            'is_approved' => ['boolean'],
            'language'     => ['nullable', 'string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
