<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\ResponseService;
use App\Services\SellerService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private SellerService $sellerService,
        private OrderService $orderService,
        private ResponseService $responseService
    )
    {}
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        
        return inertia('Dashboard', [
            'seller' => $seller
        ]);
    }

    public function vendas()
    {
        $seller = Auth::guard('seller')->user();
        
        return $this->responseService->success([
            "orders" => $this->orderService->getOrdersBySellerId($seller->id)
        ]);

    }
}
