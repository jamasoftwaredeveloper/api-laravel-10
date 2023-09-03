<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\v1\ProductSaleSeeder;
use Database\Seeders\v1\ProductSeeder;
use Database\Seeders\v1\SaleSeeder;
use Database\Seeders\v1\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);
        $this->call(SaleSeeder::class);
        $this->call(ProductSaleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
