<?php

namespace App\Console\Commands;

use App\Jobs\SendAdminDailyMail;
use App\Jobs\SendSellerComissionMail;
use App\Mail\DailyAdminReport;
use App\Mail\DailySellerReport;
use App\Models\Order;
use App\Models\Seller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-sales-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        // Relatório por vendedor
        $this->sendSellersReport($today);
        // Relatório para admin
        $this->sendAdminReport($today);
    }

    private function sendSellersReport($today)
    {
        $reportService = app(ReportService::class);
        
        Seller::with(['orders' => function ($query) use ($today) {
            $query->whereDate('created_at', $today);
        }])->get()->each(function ($seller) use ($reportService) {
            try{
                $reportService->sendReport($seller, $seller->orders);
            } catch (\Exception $e) {
                info($e->getMessage());
            }
        });
    }

    private function sendAdminReport($today)
    {
        $allOrders = Order::whereDate('created_at', $today)->get();
        SendAdminDailyMail::dispatch($allOrders);
    }
}
