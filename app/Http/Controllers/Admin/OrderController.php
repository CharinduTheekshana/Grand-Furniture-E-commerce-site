<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders           = $query->paginate(20);
        $totalOrders      = Order::count();
        $completedOrders  = Order::whereIn('status', ['delivered', 'completed'])->count();
        $processingOrders = Order::whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count();
        $cancelledOrders  = Order::where('status', 'cancelled')->count();

        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'completedOrders', 'processingOrders', 'cancelledOrders'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.color', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,completed',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Fire realtime broadcast
        try {
            event(new \App\Events\OrderStatusUpdated($order));
        } catch (\Exception $e) {}

        // Send status update email
        if ($oldStatus !== $request->status) {
            try {
                \Mail::to($order->email)->send(new \App\Mail\OrderStatusMail($order, $oldStatus));
            } catch (\Exception $e) {
                // Silent fail
            }
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status));
    }
}