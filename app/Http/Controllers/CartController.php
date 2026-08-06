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
        $cartItems = CartItem::with(['product', 'color'])->where('user_id', auth()->id())->get();
        $total = $cartItems->sum(fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity);
        return view('pages.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $colorId = $request->color_id ?: null;

        if (!auth()->check()) {
            // Save intended action in session
            session([
                'intended_action'  => 'cart',
                'intended_product' => $product->id,
                'intended_qty'     => $request->qty ?? 1,
                'intended_color'   => $colorId,
            ]);
            return response()->json(['redirect' => route('login')]);
        }

        // Same product but a different color is a separate cart line
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('color_id', $colorId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->qty ?? 1);
        } else {
            CartItem::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'color_id'   => $colorId,
                'quantity'   => $request->qty ?? 1,
            ]);
        }

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['count' => $count, 'message' => 'Added to cart!']);
    }

    // Quantity update, validation, DB save
    public function update(Request $request, CartItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);
        $item->update(['quantity' => max(1, $request->quantity)]);
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