<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
{
    $productColumns = ['id', 'name', 'slug', 'price', 'old_price', 'sale_price', 'image', 'is_featured', 'is_active', 'stock', 'category_id', 'created_at', 'offer_badge', 'offer_type', 'offer_start_date', 'offer_end_date', 'offer_status'];

    $allProducts = Product::where('is_active', true)
                          ->select($productColumns)
                          ->get();

    $homeData = [
        'allProducts'      => $allProducts,
        'newProducts'      => Product::where('is_active', true)
                                
                                ->select($productColumns)->latest()->take(10)->get(),
        'topInteresting'   => Product::where('is_active', true)
                                
                                ->select($productColumns)->inRandomOrder()->take(6)->get(),
        'featuredProducts' => Product::where('is_active', true)
                                ->where('is_featured', true)
                                
                                ->select($productColumns)->take(4)->get(),
        'saleProducts'     => Product::where('is_active', true)
                                ->whereNotNull('old_price')
                                
                                ->select($productColumns)->take(4)->get(),
        'latestBlogs'      => Blog::where('is_published', true)
                                ->select(['id', 'title', 'slug', 'content', 'image', 'created_at'])
                                ->latest()->take(6)->get(),
        'activeCoupons'    => \App\Models\Coupon::where('is_active', true)
                                ->where(function($q) { $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()); })
                                ->where(function($q) { $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit'); })
                                ->latest()->take(6)->get(),
    ];

    return view('pages.home', $homeData);
}

    public function shop(Request $request)
    {
        $perPage = (int) ($request->per_page ?? 12);
        $perPage = min(max($perPage, 6), 36);

        $query = Product::where('is_active', true)->with(['category', 'colors']);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // ── Color filter — many-to-many ──────────────────
        if ($request->color) {
            $query->whereHas('colors', fn($q) => $q->where('colors.id', $request->color));
        }

        match($request->sort ?? '') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('id', 'desc'),
        };

        $products      = $query->paginate($perPage)->withQueryString();
        $categories    = Category::withCount('products')->get();
        $totalProducts = Product::where('is_active', true)->count();
        $minPrice      = Product::where('is_active', true)->min('price') ?? 0;
        $maxPrice      = Product::where('is_active', true)->max('price') ?? 500000;

        // Colors with at least one active product
        $allColors = \App\Models\Color::whereHas('products', fn($q) => $q->where('is_active', true))->get();

        return view('pages.shop', compact(
            'products', 'categories', 'totalProducts',
            'minPrice', 'maxPrice', 'allColors'
        ));
    }
}