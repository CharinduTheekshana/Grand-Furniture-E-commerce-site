<?php
namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //  Users' items fetch, total calculate
    public function index()
    {
        if (!auth()->check()) return redirect()->route('login');
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();
        $total = $cartItems->sum(fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity);
        return view('pages.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if (!auth()->check()) {
            // Save intended action in session
            session(['intended_action' => 'cart', 'intended_product' => $product->id, 'intended_qty' => $request->qty ?? 1]);
            return response()->json(['redirect' => route('login')]);
        }

        $cartItem = CartItem::where('user_id', auth()->id())->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->increment('quantity', $request->qty ?? 1);
        } else {
            CartItem::create(['user_id' => auth()->id(), 'product_id' => $product->id, 'quantity' => $request->qty ?? 1]);
        }

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['count' => $count, 'message' => 'Added to cart!']);
    }

    // Quantity update, validation, DB save
    public function update(Request $request, CartItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);
        $item->update(['quantity' => max(10, $request->quantity)]);
        return redirect()->route('cart.index');
    }

    public function remove(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        if ($item->user_id !== auth()->id()) abort(403);
        $item->delete();
        return redirect()->route('cart.index')->with('error', 'Item removed.');
    }
}