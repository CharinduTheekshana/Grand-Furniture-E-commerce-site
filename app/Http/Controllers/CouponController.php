<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
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

        session(['coupon_code' => $coupon->code, 'coupon_id' => $coupon->id]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied! ' . ($coupon->type === 'percent' ? $coupon->value . '% off' : 'LKR ' . number_format($coupon->value, 2) . ' off'),
            'code'    => $coupon->code,
            'type'    => $coupon->type,
            'value'   => $coupon->value,
        ]);
    }

    public function remove()
    {
        session()->forget(['coupon_code', 'coupon_id']);
        return response()->json(['success' => true]);
    }
}