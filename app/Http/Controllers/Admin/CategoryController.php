<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories     = Category::withCount('products')->orderBy('name')->get();
        $totalCategories = Category::count();
        $totalProducts   = Product::count();

        return view('admin.categories.index', compact(
            'categories', 'totalCategories', 'totalProducts'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $request->name . '" created!');
    }

    public function edit(Category $category)
    {
        $category->load('products');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Set products category_id to null before deleting
        Product::where('category_id', $category->id)->update(['category_id' => null]);
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted. Products are now uncategorized.');
    }
}
