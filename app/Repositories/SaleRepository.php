<?php

namespace App\Repositories;

use App\Models\v1\Sale;
use App\Models\v1\ProductSale;

class SaleRepository
{
    public function getAllSales($order, $page)
    {
        return Sale::orderBy('created_at', $order)->paginate($page);
    }

    public function findSaleById($id)
    {
        return Sale::find($id);
    }

    public function createSale(array $data)
    {
        return Sale::create($data);
    }

    public function updateSale(Sale $sale, array $data)
    {
        return $sale->update($data);
    }

    public function deleteSale(Sale $sale)
    {
        $sale->products()->detach();
        return $sale->delete();
    }

    public function attachProducts(Sale $sale, array $products)
    {  
      
        foreach ($products as $productData) {
            $sale->products()->attach([
                $productData['id'] => ['quantity' =>  $productData['quantity']]
            ]);
        }
   
    }
}
