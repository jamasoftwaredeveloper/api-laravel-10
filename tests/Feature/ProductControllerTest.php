<?php

namespace Tests\Feature;

use App\Models\v1\Product;
use App\Models\v1\User;
use Faker\Factory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class ProductControllerTest extends TestCase
{
    use RefreshDatabase; // Reinicia la base de datos después de cada prueba

    public function testIndex()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => $faker->unique()->email(),
            'password' => '1234'
        ]);
        $this->actingAs($user);

        // Realiza una solicitud GET a la ruta de index del controlador
        $response = $this->get('api/v2/products');

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
            'email' => $faker->unique()->email(),
            'password' => '1234'
        ]);
        $this->actingAs($user);

        $product = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json',
        ]);

        $response = $this->post('api/v2/products', $product);
        $response
            ->assertStatus(201);
    }

    public function testShow()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => $faker->unique()->email(),
            'password' => '1234'
        ]);
        $this->actingAs($user);
        // Crea un producto de ejemplo en la base de datos

        $product = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];
        $product = Product::factory()->create($product);

        // Realiza una solicitud GET a la ruta de show del controlador con el ID del producto
        $response = $this->get("api/v2/products/{$product->id}");

        $response->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }

    public function testUpdate()
    {
        $faker = Factory::create();

        $user = User::factory()->create([
            'email' => $faker->unique()->email(),
            'password' => '1234'
        ]);

        $this->actingAs($user);

        $product = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];
        $product = Product::factory()->create($product);

        $productUpdate = [
            'sku' => $faker->unique()->ean13(),
            'name' => $faker->unique()->name(),
            'description' => $faker->sentence(),
            'photo' => $faker->imageUrl(),
            'price' => $faker->randomFloat(2, 100, 1000),
            'iva' => $faker->randomFloat(2, 16, 21),
            'active' => true,
        ];

        $response = $this->put("api/v2/products/{$product->id}", $productUpdate);

        $response
            ->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }

    public function testDestroy()
    {
        $faker = Factory::create();
        $user = User::factory()->create([
            'email' => $faker->unique()->email(),
            'password' => '1234'
        ]);
        $this->actingAs($user);
        $id = rand(1, 50);

        // Realiza una solicitud DELETE para eliminar el producto
        $response = $this->delete("api/v2/products/{$id}");

        $response
            ->assertStatus($response->getStatusCode() === 200 ? 200 : 202);
    }
}
