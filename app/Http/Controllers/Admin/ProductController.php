<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Events\ProductUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        $products = $query->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'stock'       => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // Upload image to storage/app/public/products/
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $file, $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $validated['slug']       = \Str::slug($validated['name']) . '-' . time();
        $validated['discount']   = $validated['discount'] ?? 0;
        $validated['stock']      = $validated['stock'] ?? 0;
        $validated['is_featured']= $request->has('is_featured') ? 1 : 0;
        $validated['is_active']  = $request->has('is_active') ? 1 : 1;

        $product = Product::create($validated);

        try { event(new ProductUpdated($product, 'created')); } catch (\Exception $e) {}

        return redirect()->route('admin.products.index')
            ->with('success', 'Product "' . $product->name . '" created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'stock'       => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('products', $file, $filename);
            $validated['image'] = 'products/' . $filename;
        }

        $validated['discount']   = $validated['discount'] ?? 0;
        $validated['stock']      = $validated['stock'] ?? 0;
        $validated['is_featured']= $request->has('is_featured') ? 1 : 0;
        $validated['is_active']  = $request->has('is_active') ? 1 : 1;

        $product->update($validated);

        try { event(new ProductUpdated($product, 'updated')); } catch (\Exception $e) {}

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}