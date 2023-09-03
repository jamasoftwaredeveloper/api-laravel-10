<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\SaleCreateRequest;
use App\Http\Requests\v1\SaleUpdateRequest;
use App\Http\Resources\v1\SaleCollection;
use App\Http\Resources\v1\SaleResource;
use App\Models\v1\Sale;
use Illuminate\Http\Request;
use App\Http\Traits\GlobalTrait;
use App\Models\v1\ProductSale;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use GlobalTrait;

    protected $order;
    protected $page;

    /**
     * Constructor de la clase SaleController.
     *
     * Inicializa las dependencias como el orden y la paginación desde la configuración.
     */
    public function __construct()
    {
        $this->order = Config::get('variable.order');
        $this->page = Config::get('variable.page');
    }

    /**
     * Obtiene una lista paginada de ventas.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $order = $request->has('order') ? $request->input('order') : $this->order;
            $page = $request->has('page') ? $request->input('page') : $this->page; // Establecer página predeterminada como 1 si no se proporciona 'page'
            $sales = Sale::orderBy('created_at', $order)->paginate($page);
            return response()->json(new SaleCollection($sales));
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * Crea una nueva venta.
     *
     * @param SaleCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaleCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedDataSale = $request->validated();
            $sale = Sale::create($validatedDataSale);
            $validatedDataProduct = $request->validated()['products'];
            foreach ($validatedDataProduct as $value) {
                $productSale = new ProductSale;
                $productSale->product_id = $value['id'];
                $productSale->sale_id = $sale->id;
                $productSale->quantity = $value['quantity'];
                $productSale->save();
            }
            DB::commit();
            return response()->json(new SaleResource($sale), 200);
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->handleException($ex);
        }
    }

    /**
     * Obtiene los detalles de una venta específica por su ID.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 202);
            }
            return response()->json(new SaleResource($sale), 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * Actualiza los detalles de una venta existente.
     *
     * @param SaleCreateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SaleUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 202);
            }
            $validatedDataSale = $request->validated();
            $sale->update($validatedDataSale);
            $validatedDataProduct = $request->validated()['products'];
            $sale->products()->detach();
            foreach ($validatedDataProduct as $value) {
                $productSale = new ProductSale;
                $productSale->product_id = $value['id'];
                $productSale->sale_id = $sale->id;
                $productSale->quantity = $value['quantity'];
                $productSale->save();
            }
            DB::commit();
            return response()->json(new SaleResource($sale));
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->handleException($ex);
        }
    }

    /**
     * Elimina una venta existente.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['info' => "Warning", 'message' => "Sale does not exist"], 202);
            }
            $sale->products()->detach();
            $sale->delete();
            return response()->json(['success' => "Success", 'message' => "Successfully deleted"], 200);
        } catch (\Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
