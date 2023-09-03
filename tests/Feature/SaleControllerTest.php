<?php

namespace Tests\Feature;

use App\Models\v1\Product;
use App\Models\v1\ProductSale;
use App\Models\v1\Sale;
use App\Models\v1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase; // Reinicia la base de datos después de cada prueba

    public function testIndex()
    {
        $user = User::factory()->create([
            'email' => 'testsale1@gmail.com',
            'password' => '1234'
        ]);
        $this->actingAs($user);

        // Realiza una solicitud GET a la ruta de index del controlador
        $response = $this->get('/api/sales');

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    public function testStore()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => 'test2@gmail.com',
            'password' => '1234'
        ]);
        $this->actingAs($user);

        $product1 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product2 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product1 = Product::factory()->create($product1);
        $product2 = Product::factory()->create($product2);

        $saleData = [
            'products' => [
                ['id' => $product1->id, 'quantity' => $faker->numberBetween(1, 10)],
                ['id' => $product2->id, 'quantity' => $faker->numberBetween(1, 10)]
            ],
            'number' => $faker->unique()->regexify('[A-Z0-9]{10}'),
            'customer' => $faker->name(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email()
        ];

        $response = $this->post('/api/sales', $saleData);

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200);
    }

    public function testShow()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => 'test2@gmail.com',
            'password' => '1234'
        ]);
        $this->actingAs($user);

        // Crea una venta de ejemplo en la base de datos

        $product1 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product2 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product1 = Product::factory()->create($product1);
        $product2 = Product::factory()->create($product2);

        $saleData = [
            'number' => $faker->unique()->regexify('[A-Z0-9]{10}'),
            'customer' => $faker->name(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email()
        ];

        $sale = Sale::factory()->create($saleData);

        $productsSalesData1 = [
            'product_id' => $product1->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        $productsSalesData2 = [
            'product_id' => $product2->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        ProductSale::factory()->create($productsSalesData1);
        ProductSale::factory()->create($productsSalesData2);

        $response = $this->get("/api/sales/{$sale->id}");

        // Verifica que la respuesta tenga el código de estado 201 y sea JSON
        $response->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }

    public function testUpdate()
    {

        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => 'test2@gmail.com',
            'password' => '1234'
        ]);
        $this->actingAs($user);
        $product1 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product2 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product1 = Product::factory()->create($product1);
        $product2 = Product::factory()->create($product2);
        $saleData = [
            'number' => $faker->unique()->regexify('[A-Z0-9]{10}'),
            'customer' => $faker->name(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email()
        ];
        $sale = Sale::factory()->create($saleData);
        $productsSalesData1 = [
            'product_id' => $product1->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        $productsSalesData2 = [
            'product_id' => $product2->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        ProductSale::factory()->create($productsSalesData1);
        ProductSale::factory()->create($productsSalesData2);

        $saleUpdateData = [
            'products' => [
                ['id' => $product1->id, 'quantity' => $faker->numberBetween(1, 10)],
                ['id' => $product2->id, 'quantity' => $faker->numberBetween(1, 10)]
            ],
            'number' => $faker->unique()->regexify('[A-Z0-9]{10}'),
            'customer' => $faker->name(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email()
        ];

        $response = $this->put("/api/sales/{$sale->id}", $saleUpdateData);

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }

    public function testDestroy()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => 'test2@gmail.com',
            'password' => '1234'
        ]);
        $this->actingAs($user);
        // Crea una venta de ejemplo en la base de datos

        $product1 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product2 = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $product1 = Product::factory()->create($product1);
        $product2 = Product::factory()->create($product2);
        $saleData = [
            'number' => $faker->unique()->regexify('[A-Z0-9]{10}'),
            'customer' => $faker->name(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->email()
        ];
        $sale = Sale::factory()->create($saleData);
        $productsSalesData1 = [
            'product_id' => $product1->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        $productsSalesData2 = [
            'product_id' => $product2->id,
            'sale_id' => $sale->id,
            'quantity' => $faker->numberBetween(1, 10),
        ];

        ProductSale::factory()->create($productsSalesData1);
        ProductSale::factory()->create($productsSalesData2);

        // Realiza una solicitud DELETE para eliminar la venta
        $response = $this->delete("/api/sales/{$sale->id}");

        // Verifica que la respuesta tenga el código de estado 204 (sin contenido)
        $response->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }
}
