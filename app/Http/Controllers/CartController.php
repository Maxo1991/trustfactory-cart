<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\CartBackup;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $cartCollection = collect($cart);

        $total = $cartCollection->sum(fn ($item) => $item['price'] * $item['quantity']);

        return Inertia::render('Cart', [
            'cart' => array_values($cart), 
            'total' => $total,
            'cart_count' => count($cart),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
        } else {
            $product = Product::findOrFail($productId);

            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);
        
        $this->backupCart($cart);

        return back();
    }

    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int) $request->quantity);
        }

        session()->put('cart', $cart);

        $this->backupCart($cart);

        return back();
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);

        session()->put('cart', $cart);

        return back();
    }

    private function backupCart(array $cart): void
    {
        if (!Auth::check()) {
            return;
        }

        CartBackup::updateOrCreate(
            ['user_id' => Auth::id()], 
            ['items' => $cart]         
        );
    }
}
