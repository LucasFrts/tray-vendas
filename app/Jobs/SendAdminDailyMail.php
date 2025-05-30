<?php

namespace App\Jobs;

use App\Mail\DailyAdminReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendAdminDailyMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $orders
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminEmail = config('mail.admin_address', 'admin@empresa.com');

        if ($this->orders->count() > 0) {
            $totalSales = $this->orders->sum('total');
            $salesCount = $this->orders->count();

            Mail::to($adminEmail)->queue(
                new DailyAdminReport($salesCount, $totalSales)
            );
        }
    }
}
