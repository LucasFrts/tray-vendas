<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\ReportService;
use App\Services\ResponseService;
use App\Services\SellerService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
        private ResponseService $responseService,
        private SellerService $sellerService,
        private OrderService $orderService
    )
    {}

    public function dailyReport(Request $request)
    {
        $seller = $this->sellerService->find($request->seller_id);

        if (!$seller) {
            return $this->responseService->notFound();
        }

        $orders = $this->orderService->getDailyOrdersBySellerId($request->seller_id);
        $this->reportService->sendReport($seller, $orders);

        return $this->responseService->noContent();
    }
}
