<?php

namespace Database\Seeders\v1;

use App\Models\v1\ProductSale;
use Illuminate\Database\Seeder;

class ProductSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductSale::factory(20)->create();
    }
}
