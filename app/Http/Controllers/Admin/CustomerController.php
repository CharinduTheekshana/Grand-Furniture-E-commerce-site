<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $customers      = User::withCount('orders')
                              ->withSum('orders as total_spent', 'total')
                              ->latest()
                              ->paginate(20);

        $totalCustomers = User::count();
        $newCustomers   = User::whereMonth('created_at', Carbon::now()->month)->count();
        $withOrders     = User::has('orders')->count();
        $totalRevenue   = Order::whereIn('status', ['paid','processing','completed','shipped','delivered'])->sum('total');

        return view('admin.customers.index', compact(
            'customers', 'totalCustomers', 'newCustomers', 'withOrders', 'totalRevenue'
        ));
    }

    public function show(User $customer)
    {
        $orders = $customer->orders()
                    ->with('items.product')
                    ->latest()
                    ->get();

        $totalSpent    = $orders->whereIn('status', ['paid','processing','completed','shipped','delivered'])->sum('total');
        $totalOrders   = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingOrders = $orders->whereIn('status', ['pending','confirmed','processing'])->count();

        // Monthly spending chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyData[] = [
                'label'   => $month->format('M Y'),
                'revenue' => $orders->filter(fn($o) =>
                    $o->created_at->format('Y-m') === $month->format('Y-m') &&
                    in_array($o->status, ['paid','processing','completed','shipped','delivered'])
                )->sum('total'),
            ];
        }

        return view('admin.customers.show', compact(
            'customer', 'orders',
            'totalSpent', 'totalOrders', 'completedOrders', 'pendingOrders',
            'monthlyData'
        ));
    }
}