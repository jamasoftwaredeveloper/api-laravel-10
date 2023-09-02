<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ProductCreateRequest;
use App\Http\Resources\v1\ProductCollection;
use App\Http\Resources\v1\ProductResource;
use App\Models\v1\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    try {
      $order = $request->has('order') ? $request->input('order') : config('variable.order');
      $page = $request->has('page') ? $request->input('page') : config('variable.page');
      $products = Product::where('active', 1)->orderBy('created_at', $order)
        ->paginate($page);
      return response()->json(new ProductCollection($products));
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }

  public function store(ProductCreateRequest $request)
  {
    try {
      $product = Product::create($request->all());
      return response()->json(new ProductResource($product));
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }

  public function show(Product $product)
  {
    try {
      if ($product->where('active', 0)) {
        return response()->json(['info' => 'Inactivo', 'details' => "El producto $product->id, esta inactivo"], 401);
      }
      return response()->json(new ProductResource($product));
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }

  public function update(ProductCreateRequest $request, Product $product)
  {

    try {
      if ($product->where('active', 0)) {
        return response()->json(['info' => 'Inactivo', 'details' => "El producto $product->id, esta inactivo"], 401);
      }
      $product->update($request->all());
      return response()->json(new ProductResource($product));
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }

  public function destroy(Product $product)
  {
    try {
      $product->delete();
      return response()->json(['success' => "Éxito", 'details' => "Se ha elimando correctamente"], 204);
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }

  public function inactiveOrActivate(Product $product, Request $request)
  {
    try {
      $product->active = $request->active;
      $product->save();
      return response()->json(['success' => "Éxito", 'details' => "Se ha cambiado exitosamente el estado del producto $product->id"], 204);
    } catch (\Exception $ex) {
      return response()->json(['error' => 'Algo salio mal', 'details' => $ex->getMessage()], $ex->getCode());
    }
  }
}
