<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ProductCreateRequest;
use App\Http\Requests\v1\ProductUpdateRequest;
use App\Http\Resources\v1\ProductCollection;
use App\Http\Resources\v1\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Traits\GlobalTrait;
use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Productos",
 *     description="Operaciones relacionadas con los productos"
 * )
 */
class ProductController extends Controller
{
  use GlobalTrait;

  protected $productService;
  protected $order;
  protected $page;

  /**
   * Constructor de la clase ProductController.
   */
  public function __construct(ProductService $productService)
  {
    $this->productService = $productService;
    $this->order = Config::get('variable.order', 'asc');
    $this->page = Config::get('variable.page', 15);
  }
  /**
   * @OA\Get(
   *     path="/v2/products",
   *     summary="Mostrar productos",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}}, 
   *     @OA\Response(
   *         response=200,
   *         description="Mostrar todos los productos.",
   *         @OA\JsonContent(
   *             @OA\Property(property="success", type="boolean", example=true),
   *             @OA\Property(property="action", type="string", example="Listado de productos"),
   *             @OA\Property(property="type", type="string", example="product"),
   *             @OA\Property(
   *                 property="data",
   *                 type="array",
   *                 @OA\Items(
   *                     @OA\Property(property="id", type="integer", example=39),
   *                     @OA\Property(property="sku", type="string", example="6141143809762"),
   *                     @OA\Property(property="name", type="string", example="Ms. Aylin Macejkovic"),
   *                     @OA\Property(property="description", type="string", example="Quo enim repudiandae corporis."),
   *                     @OA\Property(property="photo", type="string", format="uri", example="https://via.placeholder.com/640x480.png/00cccc?text=voluptatem"),
   *                     @OA\Property(property="price", type="number", format="float", example=997.17),
   *                     @OA\Property(property="iva", type="number", format="float", example=19.16),
   *                     @OA\Property(property="active", type="integer", example=1)
   *                 )
   *             ),
   *             @OA\Property(
   *                 property="creador",
   *                 type="object",
   *                 @OA\Property(property="organization", type="string", example="Jm pramming"),
   *                 @OA\Property(property="author", type="string", example="Jefri Martínez")
   *             ),
   *             @OA\Property(
   *                 property="meta",
   *                 type="object",
   *                 @OA\Property(property="current_page", type="integer", example=1),
   *                 @OA\Property(property="from", type="integer", example=1),
   *                 @OA\Property(property="last_page", type="integer", example=4),
   *                 @OA\Property(property="path", type="string", example="http://localhost:8000/v2/products"),
   *                 @OA\Property(property="per_page", type="integer", example=15),
   *                 @OA\Property(property="to", type="integer", example=15),
   *                 @OA\Property(property="total", type="integer", example=50),
   *                 @OA\Property(
   *                     property="links",
   *                     type="object",
   *                     @OA\Property(property="first", type="string", example="http://localhost:8000/v2/products?page=1"),
   *                     @OA\Property(property="last", type="string", example="http://localhost:8000/v2/products?page=4"),
   *                     @OA\Property(property="prev", type="string", nullable=true, example=null),
   *                     @OA\Property(property="next", type="string", example="http://localhost:8000/v2/products?page=2")
   *                 )
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response="default",
   *         description="Ha ocurrido un error."
   *     )
   * )
   */
  public function index(Request $request)
  {
    try {
      $order = $request->input('order', $this->order);
      $page = $request->input('page', $this->page);
      $products = $this->productService->getAllActive($order, $page);

      return response()->json(new ProductCollection($products), 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * @OA\Post(
   *     path="/v2/products",
   *     summary="Crear un nuevo producto",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         description="Datos para crear un nuevo producto",
   *         @OA\JsonContent(
   *             type="object",
   *             required={"sku", "name", "description", "price", "iva", "active"},
   *             @OA\Property(property="sku", type="string", example="123456", description="Código único del producto"),
   *             @OA\Property(property="name", type="string", example="Nombre del producto", description="Nombre del producto"),
   *             @OA\Property(property="description", type="string", example="Descripción del producto", description="Descripción del producto"),
   *             @OA\Property(property="photo", type="string", format="uri", example="http://example.com/photo.jpg", description="URL de la foto del producto"),
   *             @OA\Property(property="price", type="number", format="float", example=19.99, description="Precio del producto"),
   *             @OA\Property(property="iva", type="number", format="float", example=0.19, description="Porcentaje de IVA del producto"),
   *             @OA\Property(property="active", type="boolean", example=true, description="Estado de disponibilidad del producto")
   *         )
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="Producto creado exitosamente.",
   *         @OA\JsonContent(
   *            type="object",
   *            properties={
   *              @OA\Property(property="sku", type="string", example="123456", description="Código único del producto"),
   *              @OA\Property(property="name", type="string", example="Nombre del producto", description="Nombre del producto"),
   *              @OA\Property(property="description", type="string", example="Descripción del producto", description="Descripción del producto"),
   *              @OA\Property(property="photo", type="string", format="uri", example="http://example.com/photo.jpg", description="URL de la foto del producto"),
   *              @OA\Property(property="price", type="number", format="float", example=19.99, description="Precio del producto"),
   *              @OA\Property(property="iva", type="number", format="float", example=0.19, description="Porcentaje de IVA del producto"),
   *              @OA\Property(property="active", type="boolean", example=true, description="Estado de disponibilidad del producto")
   *            }
   *         )
   *     ),
   *     @OA\Response(
   *         response="default",
   *         description="Ha ocurrido un error."
   *     )
   * )
   */
  public function store(ProductCreateRequest $request)
  {
    try {
      $validatedData = $request->validated();
      $product = $this->productService->createProduct($validatedData);

      return response()->json(new ProductResource($product), 201);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * @OA\Get(
   *     path="/v2/products/{id}",
   *     summary="Obtener un producto por ID",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Producto encontrado.",
   *         @OA\JsonContent(
   *            type="object",
   *            properties={
   *              @OA\Property(property="sku", type="string", example="123456", description="Código único del producto"),
   *              @OA\Property(property="name", type="string", example="Nombre del producto", description="Nombre del producto"),
   *              @OA\Property(property="description", type="string", example="Descripción del producto", description="Descripción del producto"),
   *              @OA\Property(property="photo", type="string", format="uri", example="http://example.com/photo.jpg", description="URL de la foto del producto"),
   *              @OA\Property(property="price", type="number", format="float", example=19.99, description="Precio del producto"),
   *              @OA\Property(property="iva", type="number", format="float", example=0.19, description="Porcentaje de IVA del producto"),
   *              @OA\Property(property="active", type="boolean", example=true, description="Estado de disponibilidad del producto")
   *            }
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Producto no encontrado.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=403,
   *         description="Producto inactivo.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function show($id)
  {
    try {
      $product = $this->productService->findProductById($id);
      if (!$product) {
        return response()->json(['info' => "Warning", 'message' => "Product does not exist"], 404);
      }

      if ($product->isInactive()) {
        return response()->json(['info' => 'Inactivo', 'message' => "El producto $product->id está inactivo"], 403);
      }

      return response()->json(new ProductResource($product), 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * @OA\Put(
   *     path="/v2/products/{id}",
   *     summary="Actualizar un producto existente",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         description="Datos para crear un nuevo producto",
   *         @OA\JsonContent(
   *             type="object",
   *             required={"sku", "name", "description", "price", "iva", "active"},
   *             @OA\Property(property="sku", type="string", example="123456", description="Código único del producto"),
   *             @OA\Property(property="name", type="string", example="Nombre del producto", description="Nombre del producto"),
   *             @OA\Property(property="description", type="string", example="Descripción del producto", description="Descripción del producto"),
   *             @OA\Property(property="photo", type="string", format="uri", example="http://example.com/photo.jpg", description="URL de la foto del producto"),
   *             @OA\Property(property="price", type="number", format="float", example=19.99, description="Precio del producto"),
   *             @OA\Property(property="iva", type="number", format="float", example=0.19, description="Porcentaje de IVA del producto"),
   *             @OA\Property(property="active", type="boolean", example=true, description="Estado de disponibilidad del producto")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Producto creado exitosamente.",
   *         @OA\JsonContent(
   *            type="object",
   *            properties={
   *              @OA\Property(property="sku", type="string", example="123456", description="Código único del producto"),
   *              @OA\Property(property="name", type="string", example="Nombre del producto", description="Nombre del producto"),
   *              @OA\Property(property="description", type="string", example="Descripción del producto", description="Descripción del producto"),
   *              @OA\Property(property="photo", type="string", format="uri", example="http://example.com/photo.jpg", description="URL de la foto del producto"),
   *              @OA\Property(property="price", type="number", format="float", example=19.99, description="Precio del producto"),
   *              @OA\Property(property="iva", type="number", format="float", example=0.19, description="Porcentaje de IVA del producto"),
   *              @OA\Property(property="active", type="boolean", example=true, description="Estado de disponibilidad del producto")
   *            }
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Producto no encontrado.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=403,
   *         description="Producto inactivo.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function update(ProductUpdateRequest $request, $id)
  {
    try {
      $validatedData = $request->validated();
      $product = $this->productService->findProductById($id);

      if (!$product) {
        return response()->json(['info' => "Warning", 'message' => "Product does not exist"], 404);
      }

      if ($product->isInactive()) {
        return response()->json(['info' => 'Inactivo', 'message' => "El producto $product->id está inactivo"], 403);
      }

      $updatedProduct = $this->productService->updateProduct($product, $validatedData);
      return response()->json(new ProductResource($updatedProduct), 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * @OA\Delete(
   *     path="/v2/products/{id}",
   *     summary="Eliminar un producto específico",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Producto eliminado exitosamente.",
   *         @OA\JsonContent(
   *             @OA\Property(property="success", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Producto no encontrado.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function destroy($id)
  {
    try {
      $product = $this->productService->findProductById($id);

      if (!$product) {
        return response()->json(['info' => "Warning", 'message' => "Product does not exist"], 404);
      }

      $this->productService->deleteProduct($product);
      return response()->json(['success' => "Éxito", 'message' => "Successfully deleted"], 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }

  /**
   * @OA\Put(
   *     path="/v2/products/{id}/inactiveOrActivate",
   *     summary="Cambiar estado de activación de un producto",
   *     tags={"Products"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             @OA\Property(property="active", type="boolean")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Estado del producto actualizado.",
   *         @OA\JsonContent(
   *             @OA\Property(property="success", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Producto no encontrado.",
   *         @OA\JsonContent(
   *             @OA\Property(property="info", type="string"),
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function inactiveOrActivate(Request $request, $id)
  {
    try {

      $product = $this->productService->findProductById($id);

      if (!$product) {
        return response()->json(['info' => "Warning", 'message' => "Product does not exist"], 404);
      }

      $status = $request->input('active');
      $this->productService->toggleProductStatus($product, $status);

      return response()->json(['success' => "Éxito", 'message' => "Estado del producto actualizado"], 200);
    } catch (\Exception $ex) {
      return $this->handleException($ex);
    }
  }
}
