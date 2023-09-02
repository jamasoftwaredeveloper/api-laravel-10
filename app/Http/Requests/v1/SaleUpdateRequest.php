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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Obtenemos el ID de la venta que se está actualizando desde la URL.
        $saleId = $this->route('sale');

        return [
            'number' => 'required|string|unique:sales,number,' . $saleId,
            'customer' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]{9}$/',
            'email' => 'required|string|email'
        ];
    }
}
