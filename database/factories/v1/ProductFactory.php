<?php

namespace Database\Factories\v1;

use App\Models\v1\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\v1\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->ean13(),
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->sentence(),
            'photo' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'iva' => $this->faker->randomFloat(2, 16, 21),
            'active' => true,
        ];
    }
}
