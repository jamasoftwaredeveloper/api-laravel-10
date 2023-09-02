<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 'total'=> $this->calculate_total_sale()
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'customer' => $this->customer,
            'phone' => $this->phone,
            'email' => $this->email,
            'details' => $this->products,
            'total'=>$this->calculate_total_sale()
        ];
    }
}
