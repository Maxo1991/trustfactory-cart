<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'user:id,name,email',
            'items.product:id,name'
        ])
        ->latest()
        ->get();

        return Inertia::render('Orders', [
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, Order $order)
    {
         $request->validate([
            'order' => 'required|exists:orders,id',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order = Order::with('items.product')->findOrFail($request->order);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus === 'pending') {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'status' => $newStatus
        ]);

        return response()->json(['success' => true]);
    }
}
