<?php

namespace App\Jobs;

use App\Mail\DailySellerReport;
use App\Models\Seller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendSellerComissionMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $orders,
        private Seller $seller
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $totalSales = $this->orders->sum('total');
        $totalCommissions = $this->orders->sum(function ($order) {
            return $order->commission ?? 0;
        });
        $salesCount = $this->orders->count();

        if ($salesCount > 0) {
            Mail::to($this->seller->email)->queue(
                new DailySellerReport($this->seller, $salesCount, $totalSales, $totalCommissions)
            );
        }
    }
}
