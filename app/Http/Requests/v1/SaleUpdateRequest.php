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
        $saleId = $this->route('id');

        return [
            'products' => 'required|array', // 'products' debe ser un arreglo requerido
            'products.*.id' => 'required|integer', // Cada elemento 'id' dentro de 'products' debe ser un entero
            'products.*.quantity' => 'required|integer|min:1', // Cada elemento 'quantity' dentro de 'products' debe ser un entero

            // Campos opcionales para la actualización
            'number' => ['sometimes', 'string', Rule::unique('sales', 'number')->ignore($saleId)],
            'customer' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|string|email',
        ];
    }
}

