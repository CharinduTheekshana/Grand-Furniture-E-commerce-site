<?php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        if (!auth()->check()) return redirect()->route('login');
        $wishlist = Wishlist::with('product')->where('user_id', auth()->id())->get();
        return view('pages.wishlist', compact('wishlist'));
    }

    public function toggle(Product $product)
    {
        if (!auth()->check()) {
            session(['intended_action' => 'wishlist', 'intended_product' => $product->id]);
            return response()->json(['redirect' => route('login')]);
        }

        $existing = Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->delete();
            if (request()->expectsJson()) {
                return response()->json(['status' => 'removed']);
            }
            return redirect()->route('wishlist.index')->with('error', 'Removed from wishlist.');
        }

        Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
        if (request()->expectsJson()) {
            return response()->json(['status' => 'added']);
        }
        return redirect()->route('wishlist.index')->with('success', 'Added to wishlist!');
    }
}