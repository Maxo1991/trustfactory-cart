<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Inertia::share([
            'cart' => function () {
                $cart = session('cart', []);
                return [
                    'count' => count($cart), 
                    'items' => array_values($cart),
                ];
            },
        ]);
        Vite::prefetch(concurrency: 3);
    }
}
