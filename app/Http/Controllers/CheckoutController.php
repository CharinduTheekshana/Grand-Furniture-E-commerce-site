<?php
namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Cart items + total show
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();
        $total = $cartItems->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);
        return view('pages.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $shipping = $request->shipping_method === 'free_shipping' ? 0 : 1500;
        $subtotal = $cartItems->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);
        $total    = $subtotal + $shipping;

        // Create order with pending status
        $order = Order::create([
            'user_id' => auth()->id(),
            'name'    => $request->first_name . ' ' . $request->last_name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address . ($request->address2 ? ', ' . $request->address2 : '') . ', ' . $request->city,
            'total'   => $total,
            'status'  => 'pending',
        ]);

        // Save order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->sale_price ?? $item->product->price,
            ]);
        }

        // Clear cart
        CartItem::where('user_id', auth()->id())->delete();

        // Redirect to payment page
        return redirect()->route('payment.show', $order->id);
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('pages.orders', compact('orders'));
    }

    // Order details page, only for owner
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load('items.product');
        return view('pages.order-detail', compact('order'));
    }

    public function confirm(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        
        // Only processing orders can be confirmed
        if ($order->status !== 'processing') {
            return back()->with('error', 'Order cannot be confirmed at this stage.');
        }
        
        $order->update(['status' => 'completed']);
        
        return redirect()->route('orders.index')
            ->with('success', 'Order #' . $order->id . ' confirmed as received!');
    }
}