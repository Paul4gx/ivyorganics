<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->order_status;
        $order->update($validated);

        // Send email notification if status has changed
        if ($oldStatus !== $order->order_status) {
            Mail::to($order->email)->send(new OrderStatusUpdated($order));
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
} 