<?php

namespace Tests\Feature;

use App\Models\v1\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase; // Reinicia la base de datos después de cada prueba
    public function testIndex()
    {
        // Crea productos de ejemplo en la base de datos
        Product::factory(5)->create();

        // Realiza una solicitud GET a la ruta de index del controlador
        $response = $this->get('/api/v1/products');

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    public function testStore()
    {
        // Crea un array de datos de ejemplo para la creación de producto
        $productData = Product::factory()->make()->toArray();

        // Realiza una solicitud POST para almacenar un nuevo producto
        $response = $this->postJson('/api/v1/products', $productData);

        // Verifica que la respuesta tenga el código de estado 201 y sea JSON
        $response->assertStatus(201)->assertJsonStructure([
            'data',
        ]);
    }

    public function testShow()
    {
        // Crea un producto de ejemplo en la base de datos
        $product = Product::factory()->create();

        // Realiza una solicitud GET a la ruta de show del controlador con el ID del producto
        $response = $this->get("/api/v1/products/{$product->id}");

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
        ]);
    }

    public function testUpdate()
    {
        // Crea un producto de ejemplo en la base de datos
        $product = Product::factory()->create();

        // Crea un array de datos de ejemplo para la actualización de producto
        $productUpdateData = Product::factory()->make()->toArray();

        // Realiza una solicitud PUT para actualizar el producto
        $response = $this->putJson("/api/v1/products/{$product->id}", $productUpdateData);

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
        ]);
    }

    public function testDestroy()
    {
        // Crea un producto de ejemplo en la base de datos
        $product = Product::factory()->create();

        // Realiza una solicitud DELETE para eliminar el producto
        $response = $this->delete("/api/v1/products/{$product->id}");

        // Verifica que la respuesta tenga el código de estado 204 (sin contenido)
        $response->assertStatus(204);

        // Verifica que el producto haya sido eliminado de la base de datos
        $this->assertDeleted($product);
    }
}
