<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService
{
    protected $saleRepository;

    public function __construct(SaleRepository $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    public function getAllSales($order, $page)
    {
        return $this->saleRepository->getAllSales($order, $page);
    }

    public function createSale(array $saleData, array $products)
    {
        DB::beginTransaction();
        try {
           
            $sale = $this->saleRepository->createSale($saleData);
            $this->saleRepository->attachProducts($sale, $products);
            DB::commit();
            return $sale;
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error($ex->getMessage());
            throw $ex;
        }
    }

    public function findSaleById($id)
    {
        return $this->saleRepository->findSaleById($id);
    }

    public function updateSale($saleId, array $saleData, array $products)
    {
        $sale = $this->saleRepository->findSaleById($saleId);
        if (!$sale) {
            return null;
        }

        DB::beginTransaction();
        try {
            $this->saleRepository->updateSale($sale, $saleData);
            $sale->products()->detach();
            $this->saleRepository->attachProducts($sale, $products);
            DB::commit();
            return $sale;
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteSale($saleId)
    {
        $sale = $this->saleRepository->findSaleById($saleId);
        if (!$sale) {
            return null;
        }
        
        return $this->saleRepository->deleteSale($sale);
    }
}
