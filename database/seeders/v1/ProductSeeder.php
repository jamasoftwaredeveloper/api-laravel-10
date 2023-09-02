<?php

namespace Database\Seeders\v1;

use App\Models\v1\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Product::factory(50)->create();
  }
}
