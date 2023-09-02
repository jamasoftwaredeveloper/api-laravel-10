<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;
    protected $table = 'products_sales';

    protected $fillable = [
        'product_id',
        'sale_id',
        'quantity',
    ];

    // Definir las relaciones con los modelos Product y Sale
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity');
    }

}
