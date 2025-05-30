<?php

namespace App\Services;

use App\Jobs\SendSellerComissionMail;

class ReportService
{
    public function __construct(
        private OrderService $orderService,
        private SellerService $sellerService
    ) {}

    public function sendReport($seller, $orders)
    {
        SendSellerComissionMail::dispatch($orders, $seller);
    }
}