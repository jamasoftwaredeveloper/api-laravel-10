<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|string|unique:sales',
            'customer' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]{9}$/',
            'email' => 'required|string|email',
            'products.*' => ['required', Rule::exists('products', 'id')],
        ];
    }
}
