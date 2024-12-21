<?php

namespace App\Repositories\Interfaces;

use App\Models\v1\Sale;

interface SaleRepositoryInterface
{
    /**
     * Obtener todas las ventas con paginación.
     *
     * @param string $order
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllSales($order, $page);

    /**
     * Buscar una venta por ID.
     *
     * @param int $id
     * @return \App\Models\v1\Sale|null
     */
    public function findSaleById($id);

    /**
     * Crear una nueva venta.
     *
     * @param array $data
     * @return \App\Models\v1\Sale
     */
    public function createSale(array $data);

    /**
     * Actualizar una venta existente.
     *
     * @param \App\Models\v1\Sale $sale
     * @param array $data
     * @return bool
     */
    public function updateSale(Sale $sale, array $data);

    /**
     * Eliminar una venta.
     *
     * @param \App\Models\v1\Sale $sale
     * @return bool
     */
    public function deleteSale(Sale $sale);

    /**
     * Asociar productos a una venta.
     *
     * @param \App\Models\v1\Sale $sale
     * @param array $products
     * @return void
     */
    public function attachProducts(Sale $sale, array $products);
}
