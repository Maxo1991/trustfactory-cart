<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class DailyOrdersReport extends Command
{
    protected $signature = 'orders:daily-report';

    protected $description = 'Get all orders from yesterday';

    public function handle()
    {
        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();

        $orders = Order::whereBetween('created_at', [$start, $end])->get();

        $this->info('Orders last day: ' . $orders->count());

        foreach ($orders as $order) {
            $this->line(
                "Order #{$order->id} | User: {$order->user_id} | Total: {$order->total} | Status: {$order->status}"
            );
        }

        return Command::SUCCESS;
    }
}
