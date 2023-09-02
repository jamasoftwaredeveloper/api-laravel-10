<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SaleCollection;
use App\Http\Resources\v1\SaleResource;
use App\Models\v1\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{

    public function index(Request $request)
    {
        try {
            $order = $request->has('order') ? $request->input('order') : config('variable.order');
            $page = $request->has('page') ? $request->input('page') : config('variable.page'); // Establecer página predeterminada como 1 si no se proporciona 'page'
            $sales = Sale::orderBy('created_at', $order)
                ->paginate($page);
            return response()->json(new SaleCollection($sales));
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $sale = Sale::create($request->all());
            return response()->json(new SaleResource($sale));
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function show(Sale $sale)
    {
        try {
            return response()->json(new SaleResource($sale));
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function update(Request $request, Sale $sale)
    {
        try {
            $sale->update($request->all());
            return response()->json(new SaleResource($sale));
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();
            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
        }
    }
}
