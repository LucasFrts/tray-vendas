<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\OrderService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct(
        private OrderService $orderService,
        private ResponseService $responseService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageNumber = request('page', 1);
        $pageQuantity = request('quantity', 15);

        if(Auth::user() instanceof \App\Models\Seller){
            $orders = $this->orderService->getOrdersBySellerId(Auth::user()->id);
            return $this->responseService->success([
                "orders" => $orders,
                "message" => "Pedidos encontrados"
            ]);
        }

        $orders = $this->orderService->getAll(true, $pageQuantity, $pageNumber);
        return $this->responseService->success($orders);
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        if(Auth::user() instanceof \App\Models\Seller){
            $data["seller_id"] = Auth::user()->id;
        }

        return $this->responseService->created([
            "order" => $this->orderService->create($data),
            "message" => "Pedido criado com sucesso"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            return $this->responseService->success([
                "order" => $this->orderService->find($id)
            ]);
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->notFound();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        try{
            $data = $request->validated();

            if(Auth::user() instanceof \App\Models\Seller){
                $data["seller_id"] = Auth::user()->id;
            }

            return $this->responseService->success([
                "order" => $this->orderService->update($id, $data),
                "message" => "Pedido atualizado com sucess"
            ]);
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->orderService->delete($id);
            return $this->responseService->deleted();
        }
        catch(Exception $e){
            info($e);
            return $this->responseService->error();
        }
    }
}
