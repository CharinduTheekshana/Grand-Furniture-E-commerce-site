<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //new products (10), top interesting products (6), featured (4), sale products (4), blogs (6) pass (home.blade.php)
    public function index()
    {
        $productColumns = ['id', 'name', 'slug', 'price', 'old_price', 'sale_price', 'image', 'is_featured', 'is_active', 'created_at'];
        $allProducts = Product::where('is_active', true)->get();

        $homeData = [
            'allProducts'  => $allProducts,
            'newProducts' => Product::where('is_active', true)
                ->select($productColumns)
                ->latest()
                ->take(10)
                ->get(),
            'topInteresting' => Product::where('is_active', true)
                ->select($productColumns)
                ->inRandomOrder()
                ->take(6)
                ->get(),
            'featuredProducts' => Product::where('is_active', true)
                ->where('is_featured', true)
                ->select($productColumns)
                ->take(4)
                ->get(),
            'saleProducts' => Product::where('is_active', true)
                ->whereNotNull('old_price')
                ->select($productColumns)
                ->take(4)
                ->get(),
            'latestBlogs' => Blog::where('is_published', true)
                ->select(['id', 'title', 'slug', 'content', 'image', 'created_at'])
                ->latest()
                ->take(6)
                ->get(),
            
        ];

        return view('pages.home', $homeData);
    }

    // Category/search/price/sort filter, paginate(12) pass (shop.blade.php)
    public function shop(Request $request)
    {
        $perPage = (int) ($request->per_page ?? 12);
        $perPage = min(max($perPage, 6), 36);

        $query   = Product::where('is_active',true)->with('category');
        if ($request->category) $query->whereHas('category', fn($q) => $q->where('slug',$request->category));
        if ($request->q)        $query->where('name','like','%'.$request->q.'%');
        if ($request->min_price)$query->where('price','>=',$request->min_price);
        if ($request->max_price)$query->where('price','<=',$request->max_price);
        match($request->sort) {
            'price_asc'  => $query->orderBy('price','asc'),
            'price_desc' => $query->orderBy('price','desc'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('id','desc'),
        };
        $products      = $query->paginate($perPage)->withQueryString();
        $categories    = Category::withCount('products')->get();
        $totalProducts = Product::where('is_active', true)->count();

        $minPrice = Product::where('is_active', true)->min('price') ?? 0;
        $maxPrice = Product::where('is_active', true)->max('price') ?? 500000;
        return view('pages.shop', compact('products','categories','totalProducts','minPrice','maxPrice'));
    }
}
