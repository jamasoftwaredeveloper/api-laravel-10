<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Sale extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'sales';

    protected $fillable = [
        'number',
        'customer',
        'phone',
        'email',
    ];

    protected $visible = [
        'number',
        'customer',
        'phone',
        'email',
        'created_at',
        'updated_at'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_sales')
            ->withPivot('quantity');
    }
    public function calculate_total_sale()
    {
        $total_sale = 0;
        foreach ($this->products as $product) {
            $total_sale += ($product->price + ($product->price * $product->iva / 100)) * $product->pivot->quantity ; // Obtiene la cantidad desde la tabla pivote
        }

        return $total_sale;
    }
}
