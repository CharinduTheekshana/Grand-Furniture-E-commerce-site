<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──────────────────────────────────
        $productCount = Product::count();
        $blogCount    = Blog::count();
        $contactCount = Contact::count();
        $userCount    = User::count();
        $orderCount   = Order::count();
        $totalRevenue = Order::whereIn('status', ['paid','processing','completed','shipped','delivered'])
                             ->sum('total');

        // ── Revenue Report (last 12 months) ─────────────
        $revenueData   = [];
        $revenueLabels = [];
        $ordersData    = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueLabels[] = $date->format('M');
            $revenueData[]   = (float) Order::whereIn('status', ['paid','processing','completed','shipped','delivered'])
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
            $ordersData[] = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // ── Trending Products ────────────────────────────
        $trendingProducts = Product::where('is_featured', 1)
            ->whereNotNull('image')
            ->where('is_active', 1)
            ->latest()->take(5)->get();

        if ($trendingProducts->isEmpty()) {
            $trendingProducts = Product::whereNotNull('image')
                ->where('is_active', 1)->latest()->take(5)->get();
        }

        // ── Best Selling Products ────────────────────────
        $bestSelling = OrderItem::with('product')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotNull('order_items.product_id')
            ->select('order_items.product_id',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->take(5)->get();

        // ── Top Customers ────────────────────────────────
        $topCustomers = Order::with('user')
            ->select('user_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total) as total_spent'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('order_count')
            ->take(5)->get();

        // ── Top Categories ───────────────────────────────
        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(6)->get();

        // ── Recent Orders ────────────────────────────────
        $recentOrders = Order::with(['items.product', 'user'])
            ->latest()->take(8)->get();

        // ── Stock Alerts ─────────────────────────────────
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->where('is_active', 1)
            ->orderBy('stock')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'productCount', 'blogCount', 'contactCount',
            'userCount', 'orderCount', 'totalRevenue',
            'revenueData', 'revenueLabels', 'ordersData',
            'trendingProducts', 'bestSelling',
            'topCustomers', 'topCategories', 'recentOrders',
            'lowStockProducts'
        ));
    }
}