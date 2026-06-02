<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews      = Review::with(['user', 'product'])->latest()->paginate(20);
        $totalReviews = Review::count();
        $avgRating    = Review::avg(DB::raw('(quality + price + value) / 3')) ?? 0;
        $recentReviews = Review::where('created_at', '>=', now()->subDays(7))->count();

        // Positive = avg >= 3
        $positiveReviews = Review::whereRaw('(quality + price + value) / 3 >= 3')->count();
        $positivePercent = $totalReviews > 0 ? round(($positiveReviews / $totalReviews) * 100) : 0;

        // Rating breakdown (1-5 stars)
        $breakdown = [];
        $colors = [5 => 'success', 4 => 'info', 3 => 'warning', 2 => 'danger', 1 => 'danger'];
        for ($star = 5; $star >= 1; $star--) {
            $count = Review::whereRaw('ROUND((quality + price + value) / 3) = ?', [$star])->count();
            $breakdown[$star] = [
                'count'   => $count,
                'percent' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
                'color'   => $colors[$star],
            ];
        }

        return view('admin.reviews.index', compact(
            'reviews', 'totalReviews', 'avgRating',
            'recentReviews', 'positivePercent', 'breakdown'
        ));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
