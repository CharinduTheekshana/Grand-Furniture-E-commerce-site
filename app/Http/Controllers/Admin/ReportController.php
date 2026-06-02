<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();

        $paid = ['paid','processing','completed','shipped','delivered'];

        $totalRevenue  = Order::whereIn('status', $paid)->whereBetween('created_at', [$from, $to])->sum('total');
        $totalOrders   = Order::whereBetween('created_at', [$from, $to])->count();
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
        $newCustomers  = User::whereBetween('created_at', [$from, $to])->count();

        $revenueByDay = Order::whereIn('status', $paid)
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels  = $revenueByDay->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $chartRevenue = $revenueByDay->pluck('revenue')->map(fn($v) => (float)$v)->toArray();
        $chartOrders  = $revenueByDay->pluck('orders')->toArray();

        $byStatus = Order::whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('status')
            ->get();

        $topProducts = OrderItem::with('product')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('orders.status', $paid)
            ->whereNotNull('order_items.product_id')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        $orders = Order::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact(
            'from', 'to',
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'newCustomers',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'byStatus', 'topProducts', 'orders'
        ));
    }

    public function export(Request $request)
    {
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();

        $orders   = Order::with(['items.product', 'user'])->whereBetween('created_at', [$from, $to])->latest()->get();
        $filename = 'sales-report-' . $from->format('Y-m-d') . '-to-' . $to->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Phone', 'Address', 'Items', 'Total (LKR)', 'Status', 'Date']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    '#GF-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $order->name,
                    $order->email,
                    $order->phone ?? '',
                    $order->address ?? '',
                    $order->items->count(),
                    number_format($order->total, 2),
                    ucfirst($order->status),
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}