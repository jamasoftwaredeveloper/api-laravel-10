<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ProductCreateRequest;
use App\Http\Requests\v1\ProductUpdateRequest;
use App\Http\Resources\v1\ProductCollection;
use App\Http\Resources\v1\ProductResource;
use App\Models\v1\Product;
use Illuminate\Http\Request;
use App\Http\Traits\GlobalTrait;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
  use GlobalTrait;
  protected $order;
  protected $page;

  /**
   * Constructor de la clase ProductController.
   *
   * Inicializa las dependencias como el orden y la paginación desde la configuración.
   */
  public function __construct()
  {
    $this->order = Config::get('variable.order');
    $this->page = Config::get('variable.page');
  }

  /**
   * Obtiene una lista paginada de productos activos.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    try {
      $order = $request->has('order') ? $request->input('order') : $this->order;
      $page = $request->has('page') ? $request->input('page') : $this->page;
      $products = Product::where('active', 1)->orderBy('created_at', $order)->paginate($page);
      return response()->json(new ProductCollection($products), 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * Crea un nuevo producto.
   *
   * @param ProductCreateRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(ProductCreateRequest $request)
  {
    try {
      $validatedData = $request->validated(); // Validar los datos utilizando la regla definida en ProductCreateRequest
      $product = Product::create($validatedData);
      return response()->json(new ProductResource($product), 201);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * Obtiene los detalles de un producto específico por su ID.
   *
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
    try {
      $product = Product::where('id',$id)->where('active', 1);
      if (!$product) {
        return response()->json(['info' => "Aviso", 'details' => "Producto no exite"], 204);
      }
      if ($product->isInactive()) {
        return response()->json(['info' => 'Inactivo', 'details' => "El producto $product->id, está inactivo"], 401);
      }
      return response()->json(new ProductResource($product), 201);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * Obtiene los detalles de un producto específico por su ID.
   *
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(ProductUpdateRequest $request,  $id)
  {
    $product = Product::where('id',$id)->where('active', 1);
    try {
      if (!$product) {
        return response()->json(['info' => "Aviso", 'details' => "Producto no exite"], 204);
      }
      if ($product->isInactive()) {
        return response()->json(['info' => 'Inactivo', 'details' => "El producto $product->id, está inactivo"], 401);
      }
      $validatedData = $request->validated(); // Validar los datos utilizando la regla definida en ProductCreateRequest
      $product->update($validatedData);
      return response()->json(new ProductResource($product), 201);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * Obtiene los detalles de un producto específico por su ID.
   *
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    try {

      $product = Product::find($id);

      if (!$product) {
        return response()->json(['info' => "Aviso", 'details' => "Producto no exite"], 200);
      }

      $product->delete();
      return response()->json(['success' => "Éxito", 'details' => "Se ha elimando correctamente"], 204);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * Cambia el estado de activación de un producto.
   *
   * @param Product $product
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function inactiveOrActivate(Product $product, Request $request)
  {
    try {
      $product->active = $request->active;
      $product->save();
      return response()->json(['success' => "Éxito", 'details' => "Se ha cambiado exitosamente el estado del producto $product->id"], 204);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }
}
