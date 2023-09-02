<?php

namespace Database\Factories\v1;

use App\Models\v1\Product;
use App\Models\v1\ProductSale;
use App\Models\v1\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\v1\ProductSale>
 */
class ProductSaleFactory extends Factory
{
    protected $model = ProductSale::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sale = Sale::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        return [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
