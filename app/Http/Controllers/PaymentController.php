<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Show payment page
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Order already processed.');
        }
        return view('pages.payment', compact('order'));
    }

    // Process payment
    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $request->validate([
            'card_number' => 'required',
            'card_holder' => 'required',
            'expiry'      => 'required',
            'cvv'         => 'required|digits:3',
        ]);

        // Simulate payment processing (always succeeds in demo)
        // In production: replace with real PayHere/Stripe API call

        // Mark order as completed
        $order->update(['status' => 'paid']);

        return redirect()->route('orders.index')
            ->with('success', '🎉 Payment successful! Order' . $order->id . ' confirmed.');
    }
}