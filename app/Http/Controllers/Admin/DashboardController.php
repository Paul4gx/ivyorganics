<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenueNG = Order::where('payment_status', 'paid')->where('country', 'NG')->sum('total');
        $totalRevenueUSD = Order::where('payment_status', 'paid')->where('country','!=', 'NG')->sum('total');
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        $recentOrders = Order::with('order_items')
            ->latest()
            ->take(5)
            ->get();
            
        $topProducts = Product::withCount(['orders' => function($query) {
            $query->where('payment_status', 'paid');
        }])
        ->orderBy('orders_count', 'desc')
        ->take(5)
        ->get();
        
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