<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Events\ProductUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'colors'])->latest();
        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        $products      = $query->get();
        $totalProducts = Product::count();
        $inStock       = Product::where('stock', '>', 0)->count();
        $outOfStock    = Product::where('stock', 0)->count();
        $totalSold     = \App\Models\OrderItem::whereNotNull('product_id')->sum('quantity');
        return view('admin.products.index', compact('products', 'totalProducts', 'inStock', 'outOfStock', 'totalSold'));
    }

    public function create()
    {
        $categories = Category::all();
        $allColors  = Color::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'allColors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'old_price'        => 'nullable|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0|max:100',
            'stock'            => 'required|integer|min:0',
            'brand'            => 'nullable|string|max:100',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'offer_badge'      => 'nullable|string|max:50',
            'offer_type'       => 'nullable|string|max:50',
            'offer_start_date' => 'nullable|date',
            'offer_end_date'   => 'nullable|date',
            'offer_status'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $file, $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $validated['slug']             = Str::slug($validated['name']) . '-' . time();
        $validated['discount']         = $validated['discount'] ?? 0;
        $validated['stock']            = $validated['stock'] ?? 0;
        $validated['brand']            = $request->brand;
        $validated['is_featured']      = $request->has('is_featured') ? 1 : 0;
        $validated['is_active']        = $request->has('is_active') ? 1 : 0;
        $validated['offer_badge']      = $request->offer_badge;
        $validated['offer_type']       = $request->offer_type;
        $validated['offer_start_date'] = $request->offer_start_date;
        $validated['offer_end_date']   = $request->offer_end_date;
        $validated['offer_status']     = $request->has('offer_status') ? 1 : 0;

        $product = Product::create($validated);

        if ($request->filled('color_ids')) {
            $product->colors()->sync($request->color_ids);
        } else {
            $product->colors()->detach();
        }

        event(new ProductUpdated($product, 'created'));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product "' . $product->name . '" created successfully!');
    }

    public function edit(Product $product)
    {
        $categories       = Category::all();
        $allColors        = Color::orderBy('name')->get();
        $selectedColorIds = $product->colors->pluck('id')->toArray();
        return view('admin.products.edit', compact('product', 'categories', 'allColors', 'selectedColorIds'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'old_price'        => 'nullable|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0|max:100',
            'stock'            => 'required|integer|min:0',
            'brand'            => 'nullable|string|max:100',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'offer_badge'      => 'nullable|string|max:50',
            'offer_type'       => 'nullable|string|max:50',
            'offer_start_date' => 'nullable|date',
            'offer_end_date'   => 'nullable|date',
            'offer_status'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $file     = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $file, $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $validated['discount']         = $validated['discount'] ?? 0;
        $validated['stock']            = $validated['stock'] ?? 0;
        $validated['brand']            = $request->brand;
        $validated['is_featured']      = $request->has('is_featured') ? 1 : 0;
        $validated['is_active']        = $request->has('is_active') ? 1 : 0;
        $validated['offer_badge']      = $request->offer_badge;
        $validated['offer_type']       = $request->offer_type;
        $validated['offer_start_date'] = $request->offer_start_date;
        $validated['offer_end_date']   = $request->offer_end_date;
        $validated['offer_status']     = $request->has('offer_status') ? 1 : 0;

        $product->update($validated);

        if ($request->filled('color_ids')) {
            $product->colors()->sync($request->color_ids);
        } else {
            $product->colors()->detach();
        }

        event(new ProductUpdated($product, 'updated'));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->colors()->detach();
        $product->delete();

        event(new ProductUpdated(null, 'deleted'));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}