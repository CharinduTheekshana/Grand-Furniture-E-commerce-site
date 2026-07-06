<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.'], 422);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'This coupon is expired or inactive.'], 422);
        }

        // Calculate cart subtotal
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();
        $subtotal = $cartItems->sum(fn($i) => ($i->product->sale_price ?? $i->product->price) * $i->quantity);

        // Check minimum order
        if ($coupon->min_order > 0 && $subtotal < $coupon->min_order) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order of LKR ' . number_format($coupon->min_order, 2) . ' required for this coupon.',
            ], 422);
        }

        $discount = $coupon->getDiscountAmount($subtotal);

        session(['coupon_code' => $coupon->code, 'coupon_id' => $coupon->id]);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied! ' . ($coupon->type === 'percent'
                ? $coupon->value . '% off'
                : 'LKR ' . number_format($coupon->value, 2) . ' off'),
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]);
    }

    public function remove()
    {
        session()->forget(['coupon_code', 'coupon_id']);
        return response()->json(['success' => true]);
    }
}