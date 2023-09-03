<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class SaleCreateRequest extends FormRequest
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

            'products' => 'required|array', // 'products' debe ser un arreglo requerido
            'products.*.id' => 'required|integer', // Cada elemento 'id' dentro de 'products' debe ser un entero
            'products.*.quantity' => 'required|integer|min:1', // Cada elemento 'quantity' dentro de 'products' debe ser un entero
            'sale.*.number' => 'required|string|unique:sales',
            'sale.*.customer' => 'required|string',
            'sale.*.phone' => 'required|string|regex:/^[0-9]{9}$/',
            'sale.*.email' => 'required|string|email'
        ];
    }
}
