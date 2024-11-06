<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\SaleCreateRequest;
use App\Http\Requests\v1\SaleUpdateRequest;
use App\Http\Resources\v1\SaleCollection;
use App\Http\Resources\v1\SaleResource;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Traits\GlobalTrait;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Ventas",
 *     description="Operaciones relacionadas con las ventas"
 * )
 */
class SaleController extends Controller
{
    use GlobalTrait;

    protected $saleService;
    protected $order;
    protected $page;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
        $this->order = Config::get('variable.order', 'asc');
        $this->page = Config::get('variable.page', 15);
    }

    /**
     * @OA\Get(
     *     path="/sales",
     *     summary="Mostrar ventas",
     *     tags={"Sales"},
     *     security={{"bearerAuth": {}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todos los ventas.",
     *         @OA\JsonContent(
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         description="Indica si la solicitud fue exitosa"
     *     ),
     *     @OA\Property(
     *         property="action",
     *         type="string",
     *         description="Descripción de la acción realizada"
     *     ),
     *     @OA\Property(
     *         property="type",
     *         type="string",
     *         description="Tipo de dato devuelto"
     *     ),
     *     @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *  *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Identificador único de la venta"
     *     ),
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="details",
     *         type="array",
     *         @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=39),
     *                 @OA\Property(property="sku", type="string", example="6141143809762"),
     *                 @OA\Property(property="name", type="string", example="Ms. Aylin Macejkovic"),
     *                 @OA\Property(property="description", type="string", example="Quo enim repudiandae corporis."),
     *                 @OA\Property(property="photo", type="string", format="uri", example="https://via.placeholder.com/640x480.png/00cccc?text=voluptatem"),
     *                 @OA\Property(property="price", type="number", format="float", example=997.17),
     *                 @OA\Property(property="iva", type="number", format="float", example=19.16),
     *                 @OA\Property(property="active", type="integer", example=1)
     *          ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *     @OA\Property(
     *         property="total",
     *         type="number",
     *         format="float",
     *         description="Total de la venta calculado"
     *     )
     * ),
     *         description="Colección de ventas"
     *     ),
     *     @OA\Property(
     *         property="creador",
     *         type="object",
     *         @OA\Property(
     *             property="organization",
     *             type="string",
     *             description="Nombre de la organización creadora"
     *         ),
     *         @OA\Property(
     *             property="author",
     *             type="string",
     *             description="Nombre del autor"
     *         ),
     *         description="Información del creador de la respuesta"
     *     ),
     *     @OA\Property(
     *         property="meta",
     *         type="object",
     *         @OA\Property(
     *             property="current_page",
     *             type="integer",
     *             description="Número de la página actual"
     *         ),
     *         @OA\Property(
     *             property="from",
     *             type="integer",
     *             description="Índice del primer elemento en la página"
     *         ),
     *         @OA\Property(
     *             property="last_page",
     *             type="integer",
     *             description="Número de la última página"
     *         ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             description="Ruta del recurso"
     *         ),
     *         @OA\Property(
     *             property="per_page",
     *             type="integer",
     *             description="Número de elementos por página"
     *         ),
     *         @OA\Property(
     *             property="to",
     *             type="integer",
     *             description="Índice del último elemento en la página"
     *         ),
     *         @OA\Property(
     *             property="total",
     *             type="integer",
     *             description="Número total de elementos"
     *         ),
     *         @OA\Property(
     *             property="links",
     *             type="object",
     *             @OA\Property(
     *                 property="first",
     *                 type="string",
     *                 description="URL de la primera página"
     *             ),
     *             @OA\Property(
     *                 property="last",
     *                 type="string",
     *                 description="URL de la última página"
     *             ),
     *             @OA\Property(
     *                 property="prev",
     *                 type="string",
     *                 nullable=true,
     *                 description="URL de la página anterior"
     *             ),
     *             @OA\Property(
     *                 property="next",
     *                 type="string",
     *                 nullable=true,
     *                 description="URL de la siguiente página"
     *             ),
     *             description="Enlaces de paginación"
     *         ),
     *         description="Metadatos sobre la paginación"
     *          ),
     *         ),
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
            $sales = $this->saleService->getAllSales($order, $page);

            return response()->json(new SaleCollection($sales));
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * @OA\Post(
     *     path="/sales",
     *     summary="Crear un nueva venta",
     *     tags={"Sales"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear un nueva venta",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"number", "customer", "phone", "email", "products"},
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="products",
     *         type="array",
     *         @OA\Items(
     *                @OA\Property(property="id", type="string", example="1"),
     *                @OA\Property(property="quantity", type="number", example="1")
     * 
     * ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *      )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Venta creada exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "number", "customer", "phone", "email", "products", "total"},
     *      @OA\Property(
     *         property="id",
     *         type="string",
     *         description="Id"
     *     ),
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="products",
     *         type="array",
     *         @OA\Items(
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="quantity", type="number", example="1")
     *          ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *     @OA\Property(
     *         property="total",
     *         type="string",
     *         description="Total de la compra"
     *     ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function store(SaleCreateRequest $request)
    {
        try {
            $saleData = $request->validated();
            $products = $saleData['products'];
            unset($saleData['products']);

            $sale = $this->saleService->createSale($saleData, $products);

            return response()->json(new SaleResource($sale), 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
    /**
     * @OA\Get(
     *     path="/v2/sales/{id}",
     *     summary="Mostrar información de una venta específica",
     *     tags={"Sales"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos para crear un nueva venta",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "number", "customer", "phone", "email", "details", "total"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Identificador único de la venta"
     *     ),
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="details",
     *         type="array",
     *         @OA\Items(
     *                 @OA\Property(property="sku", type="string", example="6141143809762"),
     *                 @OA\Property(property="name", type="string", example="Ms. Aylin Macejkovic"),
     *                 @OA\Property(property="description", type="string", example="Quo enim repudiandae corporis."),
     *                 @OA\Property(property="photo", type="string", format="uri", example="https://via.placeholder.com/640x480.png/00cccc?text=voluptatem"),
     *                 @OA\Property(property="price", type="number", format="float", example=997.17),
     *                 @OA\Property(property="iva", type="number", format="float", example=19.16),
     *                 @OA\Property(property="active", type="integer", example=1)
     * 
     * ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *     @OA\Property(
     *         property="total",
     *         type="number",
     *         format="float",
     *         description="Total de la venta calculado"
     *     )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="info", type="string", example="Warning"),
     *             @OA\Property(property="message", type="string", example="Sale does not exist")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Error inesperado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $sale = $this->saleService->findSaleById($id);
            if (!$sale) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 404);
            }
            return response()->json(new SaleResource($sale), 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
    /**
     * @OA\Put(
     *     path="/v2/sales/{id}",
     *     summary="Actualizar una venta",
     *     tags={"Sales"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear un nueva venta",
     *         @OA\JsonContent(
     *             type="object",
     *             required={ "number", "customer", "phone", "email", "products"},
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="products",
     *         type="array",
     *         @OA\Items(
     *                 @OA\Property(property="id", type="string", example="4"),
     *                 @OA\Property(property="quantity", type="number", example="4"),
     *          ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta actualizada exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "number", "customer", "phone", "email", "products", "total"},
     *      @OA\Property(
     *         property="id",
     *         type="string",
     *         description="Id"
     *     ),
     *     @OA\Property(
     *         property="number",
     *         type="string",
     *         description="Número de la venta"
     *     ),
     *     @OA\Property(
     *         property="customer",
     *         type="string",
     *         description="Nombre del cliente"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="Teléfono del cliente"
     *     ),
     *     @OA\Property(
     *         property="email",
     *         type="string",
     *         format="email",
     *         description="Correo electrónico del cliente"
     *     ),
     *     @OA\Property(
     *         property="products",
     *         type="array",
     *         @OA\Items(
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="quantity", type="number", example="1")
     *          ),
     *         description="Detalles de los productos en la venta"
     *     ),
     *     @OA\Property(
     *         property="total",
     *         type="string",
     *         description="Total de la compra"
     *     ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="info", type="string", example="Warning"),
     *             @OA\Property(property="message", type="string", example="Sale does not exist")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Error inesperado"
     *     )
     * )
     */
    public function update(SaleUpdateRequest $request, $id)
    {
        try {
            $saleData = $request->validated();
            $products = $saleData['products'];
            unset($saleData['products']);

            $sale = $this->saleService->updateSale($id, $saleData, $products);
            if (!$sale) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 404);
            }

            return response()->json(new SaleResource($sale), 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
    /**
     * @OA\Delete(
     *     path="/v2/sales/{id}",
     *     summary="Eliminar una venta",
     *     tags={"Sales"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la venta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Successfully deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="info", type="string", example="Warning"),
     *             @OA\Property(property="message", type="string", example="Sale does not exist")
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Error inesperado"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $result = $this->saleService->deleteSale($id);
            if (!$result) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 404);
            }

            return response()->json(['success' => "Success", 'message' => "Successfully deleted"], 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
