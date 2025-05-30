<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = Seller::all();

        if ($sellers->isEmpty()) {
            $sellers = Seller::factory()->count(10)->create();
        }

        Order::factory()->count(30)->make()->each(function ($order) use ($sellers) {
            $order->seller_id = $sellers->random()->id;
            $order->save();
        });
    }
}
