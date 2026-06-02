<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons        = Coupon::latest()->get();
        $totalCoupons   = Coupon::count();
        $activeCoupons  = Coupon::where('is_active', true)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', today()))
            ->count();
        $totalUsed      = Coupon::sum('used_count');
        $expiredCoupons = Coupon::where('expires_at', '<', today())->count();

        return view('admin.coupons.index', compact(
            'coupons', 'totalCoupons', 'activeCoupons', 'totalUsed', 'expiredCoupons'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0.01',
            'min_order'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date|after:today',
            'is_active'   => 'nullable|boolean',
        ]);

        Coupon::create([
            'code'        => strtoupper($request->code),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_order'   => $request->min_order ?? 0,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon "' . strtoupper($request->code) . '" created!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0.01',
            'min_order'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        $coupon->update([
            'code'        => strtoupper($request->code),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_order'   => $request->min_order ?? 0,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted.');
    }
}