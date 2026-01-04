<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return Inertia::render('Dashboard', [
            'products' => $products,
        ]);
    }
}
