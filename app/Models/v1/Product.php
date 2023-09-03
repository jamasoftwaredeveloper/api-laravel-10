<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'name',
        'description',
        'photo',
        'price',
        'iva',
        'active'
    ];
    public function toArray()
    {
        $quantity = $this->pivot ? $this->pivot->quantity : 1;
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->photo,
            'price' => $this->price,
            'quantity' => $quantity,
            'sub_total' => ($this->price + ($this->price * $this->iva / 100)) * $quantity,
            'iva' => $this->iva,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'products_sales')
            ->withPivot('quantity')->withDefault(['quantity' => 0]);
    }

    /**
     * Comprueba si el producto está inactivo.
     *
     * @return bool
     */
    public function isInactive()
    {
        return $this->active === 0;
    }
}
