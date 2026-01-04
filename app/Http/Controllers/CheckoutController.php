<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Jobs\LowStockJob;
use Illuminate\Support\Facades\DB;
use App\Models\CartBackup;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return Inertia::render('Checkout', [
            'cart' => array_values($cart),
            'total' => $total,
        ]);
    }

    public function store()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('dashboard')->with('error', 'Your cart is empty');
        }

        DB::transaction(function () use ($cart, &$order) {

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
            ]);

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                
                if (
                    $product->stock <= $product->low_stock_threshold &&
                    ! $product->low_stock_notified
                ) {
                    LowStockJob::dispatch($product);
                    $product->update(['low_stock_notified' => true]);
                }
            }
        });

        session()->forget('cart');

        CartBackup::where('user_id', Auth::id())->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Order placed successfully!');
    }
}
