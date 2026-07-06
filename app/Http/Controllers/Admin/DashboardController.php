<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ───────────────────────────────
        $orderCount       = Order::count();
        $userCount        = User::count();
        $productCount     = Product::where('is_active', true)->count();
        $totalRevenue     = Order::where('status', '!=', 'cancelled')->sum('total');

        // ── Recent Orders ────────────────────────────
        $recentOrders     = Order::with(['user', 'items'])
                                ->latest()->take(6)->get();

        // ── Low Stock Products ───────────────────────
        $lowStockProducts = Product::with('category')
                                ->where('stock', '<=', 5)
                                ->where('is_active', true)
                                ->get();

        // ── Trending Products ────────────────────────
        $trendingProducts = Product::where('is_active', true)
                                ->where('stock', '>', 0)
                                ->latest()
                                ->take(5)
                                ->get();

        // ── Top Customers ────────────────────────────
        $topCustomers = Order::with('user')
            ->select('user_id',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as total_spent'))
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(6)
            ->get();

        // ── Best Selling Products ────────────────────
        $bestSelling = OrderItem::with('product')
            ->select('product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * price) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(6)
            ->get();

        // ── Revenue Chart (last 7 days) ──────────────
        $revenueLabels = [];
        $revenueData   = [];
        $ordersData    = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenueLabels[] = $date->format('d M');

            $revenueData[] = Order::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            $ordersData[] = Order::whereDate('created_at', $date)->count();
        }

        // ── Offer Stats ──────────────────────────────
        $activeOffers  = Product::where('offer_status', true)
            ->whereNotNull('offer_badge')
            ->where(fn($q) => $q->whereNull('offer_end_date')
                            ->orWhere('offer_end_date', '>=', now()))
            ->count();

        $expiredOffers = Product::where('offer_status', true)
            ->whereNotNull('offer_badge')
            ->where('offer_end_date', '<', now())
            ->count();

            // ── Top Categories ───────────────────────────
        $topCategories = \App\Models\Category::withCount('products')
            ->withSum(['products as category_revenue' => function($q) {
                $q->join('order_items', 'products.id', '=', 'order_items.product_id');
            }], 'order_items.price')
            ->withCount(['products as category_orders' => function($q) {
                $q->join('order_items', 'products.id', '=', 'order_items.product_id');
            }])
            ->orderByDesc('category_revenue')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'orderCount', 'userCount', 'productCount', 'totalRevenue',
            'recentOrders', 'lowStockProducts', 'trendingProducts',
            'topCustomers', 'bestSelling', 'topCategories',
            'revenueLabels', 'revenueData', 'ordersData',
            'activeOffers', 'expiredOffers'
        ));

    }
    public function search(Request $request)
    {
        $q     = trim($request->q);
        $words = array_filter(explode(' ', $q));

        $products = Product::where(function($query) use ($q, $words) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
            foreach ($words as $word) {
                $query->orWhere('name', 'like', '%' . $word . '%');
            }
        })
        ->take(5)
        ->get(['id', 'name', 'price', 'stock'])
        ->map(fn($p) => [
            'id'    => $p->id,
            'name'  => $p->name,
            'price' => number_format($p->price, 2),
            'stock' => $p->stock,
        ]);

        $orders = Order::where(function($query) use ($q, $words) {
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('id', $q);
            foreach ($words as $word) {
                $query->orWhere('name', 'like', '%' . $word . '%');
            }
        })
        ->take(5)
        ->get(['id', 'name', 'total', 'status'])
        ->map(fn($o) => [
            'id'     => $o->id,
            'name'   => $o->name,
            'total'  => number_format($o->total, 2),
            'status' => $o->status,
        ]);

        return response()->json(compact('products', 'orders'));
    }
}