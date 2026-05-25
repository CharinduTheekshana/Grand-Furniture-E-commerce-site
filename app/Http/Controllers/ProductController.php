<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)->get();
        $reviews = Review::with('user')->where('product_id', $product->id)->latest()->get();
        return view('pages.product-details', compact('product', 'relatedProducts', 'reviews'));
    }

    //Review form submit, validate, DB save
    public function review(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $request->validate([
            'nickname' => 'required|string|max:100',
            'summary'  => 'required|string|max:255',
            'review'   => 'required|string',
            'quality'  => 'required|integer|min:1|max:5',
            'price'    => 'required|integer|min:1|max:5',
            'value'    => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'nickname'   => $request->nickname,
            'summary'    => $request->summary,
            'review'     => $request->review,
            'quality'    => $request->quality,
            'price'      => $request->price,
            'value'      => $request->value,
        ]);

        return redirect('/product/' . $product->slug)
            ->with('review_success', 'Your review has been submitted successfully!');
    }

    // User's own reviews page
    public function myReviews()
    {
        $reviews = Review::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('pages.reviews', compact('reviews'));
    }
}