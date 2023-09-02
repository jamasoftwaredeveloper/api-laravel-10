<?php

namespace Tests\Feature;

use App\Models\v1\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase; // Reinicia la base de datos después de cada prueba

    public function testIndex()
    {
        // Crea ventas de ejemplo en la base de datos
        Sale::factory(5)->create();

        // Realiza una solicitud GET a la ruta de index del controlador
        $response = $this->get('/api/v1/sales');

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    public function testStore()
    {
        // Crea un array de datos de ejemplo para la creación de venta
        $saleData = Sale::factory()->make()->toArray();

        // Realiza una solicitud POST para almacenar una nueva venta
        $response = $this->postJson('/api/v1/sales', $saleData);

        // Verifica que la respuesta tenga el código de estado 201 y sea JSON
        $response->assertStatus(201)->assertJsonStructure([
            'data',
        ]);
    }

    public function testShow()
    {
        // Crea una venta de ejemplo en la base de datos
        $sale = Sale::factory()->create();

        // Realiza una solicitud GET a la ruta de show del controlador con el ID de la venta
        $response = $this->get("/api/v1/sales/{$sale->id}");

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
        ]);
    }

    public function testUpdate()
    {
        // Crea una venta de ejemplo en la base de datos
        $sale = Sale::factory()->create();

        // Crea un array de datos de ejemplo para la actualización de venta
        $saleUpdateData = Sale::factory()->make()->toArray();

        // Realiza una solicitud PUT para actualizar la venta
        $response = $this->putJson("/api/v1/sales/{$sale->id}", $saleUpdateData);

        // Verifica que la respuesta tenga el código de estado 200 y sea JSON
        $response->assertStatus(200)->assertJsonStructure([
            'data',
        ]);
    }

    public function testDestroy()
    {
        // Crea una venta de ejemplo en la base de datos
        $sale = Sale::factory()->create();

        // Realiza una solicitud DELETE para eliminar la venta
        $response = $this->delete("/api/v1/sales/{$sale->id}");

        // Verifica que la respuesta tenga el código de estado 204 (sin contenido)
        $response->assertStatus(204);

        // Verifica que la venta haya sido eliminada de la base de datos
        $this->assertDeleted($sale);
    }
}
