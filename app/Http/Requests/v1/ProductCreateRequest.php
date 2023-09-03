<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|unique:products',
            'description' => 'required|string',
            'photo' => 'nullable',
            'price' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'active' => 'required|boolean'
        ];
    }
}
