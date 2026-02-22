<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'brands' => Brand::count(),
            'categories' => Category::count(),
            'reviews' => Review::count(),
        ];

        $recentProducts = Product::with('brand', 'category')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', compact('stats', 'recentProducts'));
    }
}
