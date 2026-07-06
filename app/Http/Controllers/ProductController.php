<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product        =   Product::with(['colors', 'images'])
                                ->where('slug', $slug)
                                ->where('is_active', true)
                                ->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->where('is_active', true)->where('is_active', true)
                                ->where('stock', '>', 0)
                                ->take(4)
                                ->get();
        $reviews         = Review::with('user')->where('product_id', $product->id)->latest()->get();
        $avgRating       = $reviews->count() > 0
                            ? (int) round($reviews->avg(fn($r) => ($r->quality + $r->price + $r->value) / 3))
                            : 3;

        $bsPool = Product::where('is_active', true)
                                ->where('id', '!=', $product->id)
                                ->where('stock', '>', 0)
                                ->inRandomOrder()
                                ->get()
                                ->unique('id')
                                ->values();
        $bsPairs = [];
        $bsCount = $bsPool->count();
        for ($i = 0; $i < min(6, $bsCount); $i++) {
            $a    = $bsPool[$i];
            $bIdx = ($i + 1) % $bsCount;
            if ($bsPool[$bIdx]->id === $a->id && $bsCount > 1) {
                $bIdx = ($bIdx + 1) % $bsCount;
            }
            $bsPairs[] = [
                'a'       => $a,
                'aRating' => (int) round(Review::where('product_id', $a->id)->avg(\DB::raw('(quality+price+value)/3')) ?? 3),
                'b'       => $bsPool[$bIdx],
                'bRating' => (int) round(Review::where('product_id', $bsPool[$bIdx]->id)->avg(\DB::raw('(quality+price+value)/3')) ?? 3),
            ];
        }

            // Only fill the thumbnail row with other products when this product
            // has no gallery images of its own — otherwise show only its own images.
            $thumbProducts = $product->images->isNotEmpty()
                ? collect()
                : Product::where('is_active', true)
                    ->where('id', '!=', $product->id)
                    ->where('stock', '>', 0)
                    ->inRandomOrder()
                    ->take(4)
                    ->get()
                    ->each(function($p) {
                        $p->avgRating = (int) round(
                            Review::where('product_id', $p->id)
                                ->avg(\DB::raw('(quality+price+value)/3')) ?? 3
                        );
                    });

        return view('pages.product-details', compact(
            'product', 'relatedProducts', 'reviews', 'avgRating', 'bsPairs', 'thumbProducts'
        ));
    }

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

    public function myReviews()
    {
        $reviews = Review::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('pages.reviews', compact('reviews'));
    }
}