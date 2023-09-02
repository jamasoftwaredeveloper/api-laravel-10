<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
        return [
            'sku'=>$this->sku,
            'name'=>$this->name,
            'description'=>$this->description,
            'photo'=>$this->photo,
            'price'=>$this->price,
            'quantity' => $this->pivot->quantity,
            'sub_total' =>($this->price + ($this->price * $this->iva/100)) * $this->pivot->quantity,
            'iva'=>$this->iva,
            'active'=>$this->active,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'products_sales')
            ->withPivot('quantity');
    }
}
