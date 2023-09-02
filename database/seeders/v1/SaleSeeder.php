<?php

namespace Database\Seeders\v1;

use App\Models\v1\Sale;
use Illuminate\Database\Seeder;


class SaleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Sale::factory(20)->create();
  }
}
