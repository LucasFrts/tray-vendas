<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\OrderService;
use App\Services\ResponseService;
use Exception;


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

        $orders = $this->orderService->getAll(true, $pageQuantity, $pageNumber);
        return $this->responseService->success($orders);
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
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
            return $this->responseService->success([
                "order" => $this->orderService->update($id, $request->validated()),
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
