<?php

namespace Tests\Feature;

use App\Models\v1\Product;
use App\Models\v1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    protected $token;
    protected $user;
    use RefreshDatabase; // Reinicia la base de datos después de cada prueba
    
    public function setUp(): void
    {
        parent::setUp();

        // Create a user
        $user = User::find(1);

        // Get the token for the user
        $token = $user->createToken('access_token');

        // Store the token in a class variable
        $this->token = $token;
    }
    
    
    
    public function testIndex()
    {
        $this->actingAs($this->user, $this->token);
        // Crea productos de ejemplo en la base de datos
        Product::factory(5)->create();

        // Realiza una solicitud GET a la ruta de index del controlador
        $response = $this->get('api/products');

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    public function testStore()
    {
        $this->actingAs($this->user, $this->token);
        $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
        ]);

        $response = $this->post('api/products', [
            'sku' => '2343432',
            'name' => 'Arroz Carolina ',
            'description' => 'Suelto, esponjoso y rendidor1. Viene enriquecido con Super Vit: B6-B12 y ácido fólico, una combinación de vitaminas que lo hacen más nutritivo1.',
            'photo' => 'arroz.png',
            'price' => 2000,
            'iva' => 19,
            'active' => 1,
        ]);
        $response
            ->assertStatus(201)
            ->assertExactJson([
                'created' => true,
            ]);
    }

    public function testShow()
    {
        $this->actingAs($this->user, $this->token);
        // Crea un producto de ejemplo en la base de datos
        $id = rand(1, 50);

        // Realiza una solicitud GET a la ruta de show del controlador con el ID del producto
        $response = $this->get("api/products/{$id}");

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(201)->assertJsonStructure([
            'data'
        ]);
    }

    public function testUpdate()
    {
        $this->actingAs($this->user, $this->token);
        $id = rand(1, 50);
        // Realiza una solicitud PUT para actualizar el producto
        $response = $this->put("api/products/{$id}",  [
            'sku' => '2343432',
            'name' => 'Arroz Carolina ',
            'description' => 'Suelto, esponjoso y rendidor1. Viene enriquecido con Super Vit: B6-B12 y ácido fólico, una combinación de vitaminas que lo hacen más nutritivo1.',
            'photo' => 'arroz.png',
            'price' => 2000,
            'iva' => 19,
            'active' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertExactJson([
                'updated' => true,
            ]);
    }

    public function testDestroy()
    {
        $this->actingAs($this->user, $this->token);
        $id = rand(1, 50);

        // Realiza una solicitud DELETE para eliminar el producto
        $response = $this->delete("api/products/{$id}");

        // Verifica que la respuesta tenga el código de estado 204 (sin contenido)
        $data = $response->json();

        // Imprime los datos de la respuesta
        var_dump($data);
        //$response->assertStatus(204);

        // Verifica que el producto haya sido eliminado de la base de datos
        // $this->assertNull(Product::find($id));
    }
}
