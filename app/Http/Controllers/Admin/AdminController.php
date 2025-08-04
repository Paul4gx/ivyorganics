<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenueNG = Order::where('payment_status', 'paid')->where('country', 'NG')->sum('total');
        $totalRevenueUSD = Order::where('payment_status', 'paid')->where('country','!=', 'NG')->sum('total');
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $pendingOrders = Order::where('payment_status', 'pending')->count();

        $recentOrders = Order::latest()
            ->take(5)
            ->get();

        // Get top products by order count
        $products = Product::all();
        $topProducts = $products->map(function ($product) {
            $product->order_items_count = $product->order_items_count;
            return $product;
        })->sortByDesc('order_items_count')
          ->take(5);

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenueNG',
            'totalRevenueUSD',
            'pendingOrders',
            'recentOrders',
            'topProducts'
        ));
    }
} 