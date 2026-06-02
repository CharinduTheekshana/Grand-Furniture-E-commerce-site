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
}
