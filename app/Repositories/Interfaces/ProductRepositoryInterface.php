<?php

namespace App\Repositories\Interfaces;

use App\Models\v1\Product;

interface ProductRepositoryInterface
{
    /**
     * Obtener todos los productos activos con paginación y orden.
     *
     * @param string $order
     * @param int $page
     * @return mixed
     */
    public function getAllActive($order, $page);

    /**
     * Crear un nuevo producto.
     *
     * @param array $data
     * @return \App\Models\v1\Product
     */
    public function create(array $data);

    /**
     * Buscar un producto por su ID.
     *
     * @param int $id
     * @return \App\Models\v1\Product|null
     */
    public function findById($id);

    /**
     * Actualizar un producto.
     *
     * @param \App\Models\v1\Product $product
     * @param array $data
     * @return \App\Models\v1\Product
     */
    public function update(Product $product, array $data);

    /**
     * Eliminar un producto.
     *
     * @param \App\Models\v1\Product $product
     * @return void
     */
    public function delete(Product $product);

    /**
     * Cambiar el estado activo de un producto.
     *
     * @param \App\Models\v1\Product $product
     * @param bool $status
     * @return void
     */
    public function changeStatus(Product $product, $status);
}
