<?php
namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['product', 'color'])->where('user_id', auth()->id())->get();
        $total = $cartItems->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);
        return view('pages.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $cartItems = CartItem::with(['product', 'color'])->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $shipping = $request->shipping_method === 'free_shipping' ? 0 : 1500;
        $subtotal = $cartItems->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);
        $total    = $subtotal + $shipping;

        // ── Apply coupon discount ────────────────────
        $couponDiscount = 0;
        $couponId       = session('coupon_id');
        if ($couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if ($coupon && $coupon->isValid()) {
                $couponDiscount = $coupon->getDiscountAmount($subtotal);
                $total          = max(0, $total - $couponDiscount);
                $coupon->increment('used_count');
                session()->forget(['coupon_code', 'coupon_id']);
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'name'    => $request->first_name . ' ' . $request->last_name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address . ($request->address2 ? ', ' . $request->address2 : '') . ', ' . $request->city,
            'total'   => $total,
            'status'  => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'color_id'   => $item->color_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->sale_price ?? $item->product->price,
            ]);
        }

        CartItem::where('user_id', auth()->id())->delete();

        event(new OrderPlaced($order));

        // Send order confirmation email
        try {
            \Mail::to($order->email)->send(new \App\Mail\OrderPlacedMail($order));
        } catch (\Exception $e) {
            
        }

        return redirect()->route('payment.show', $order->id);
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('pages.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load('items.product', 'items.color');
        return view('pages.order-detail', compact('order'));
    }

    public function confirm(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        if (!in_array($order->status, ['delivered', 'processing', 'shipped'])) {
            return back()->with('error', 'Order cannot be marked as received at this stage.');
        }
        $order->update(['status' => 'completed']);

        // Fire realtime event
        try {
            event(new \App\Events\OrderStatusUpdated($order));
        } catch (\Exception $e) {}

        return redirect()->route('orders.index')
            ->with('success', 'Order #' . $order->id . ' marked as received!');
    }
}