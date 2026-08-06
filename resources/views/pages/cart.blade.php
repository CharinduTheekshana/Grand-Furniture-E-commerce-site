@extends('layouts.app')
@section('title','Cart - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Cart</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="cart-main-area ptb-40">
    <div class="container">
        @if($cartItems->count() > 0)

        @php
            $couponCode = session('coupon_code');
            $couponObj  = $couponCode ? \App\Models\Coupon::where('code', $couponCode)->first() : null;
            $discount   = $couponObj ? $couponObj->getDiscountAmount((float)$total) : 0;
            $finalTotal = $total - $discount;
        @endphp

        <div class="row">
            <div class="col-lg-12">
                <div class="table-content table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="product-thumbnail">Image</th>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Total</th>
                                <th class="product-remove">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="{{ route('product.show',$item->product->slug) }}">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" />
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ route('product.show',$item->product->slug) }}">{{ $item->product->name }}</a>
                                    @if($item->color)
                                    <div style="font-size:12px;color:#666;margin-top:4px;">
                                        Color:
                                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;
                                                    background:{{ $item->color->color_code ?? '#ccc' }};
                                                    border:1px solid #ddd;vertical-align:middle;"></span>
                                        {{ $item->color->name }}
                                    </div>
                                    @endif
                                </td>
                                <td class="product-price">
                                    <span class="amount">LKR {{ number_format($item->product->price,2) }}</span>
                                </td>
                                <td class="product-quantity">
                                    <form action="{{ route('cart.update',$item->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="product-subtotal">LKR {{ number_format($item->product->price * $item->quantity,2) }}</td>
                                <td class="product-remove">
                                    <form action="{{ route('cart.remove',$item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:none;border:none;cursor:pointer;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    {{-- LEFT: Continue Shopping + Coupon --}}
                    <div class="col-lg-8 col-md-7">
                        <div class="buttons-cart">
                            <a href="{{ route('shop') }}">Continue Shopping</a>
                        </div>

                        <div class="coupon">
                            <h3>Coupon</h3>
                            <p>Enter your coupon code if you have one.</p>

                            {{-- Available Coupons --}}
                            @php
                                $availableCoupons = \App\Models\Coupon::where('is_active', true)
                                    ->where(function($q) { $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()); })
                                    ->where(function($q) { $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit'); })
                                    ->get();
                            @endphp

                            @if($availableCoupons->count() > 0)
                            <div style="margin-bottom:12px;">
                                <p style="font-size:12px;color:#999;margin-bottom:6px;">
                                    🎟️ Available coupons — click to apply:
                                </p>
                                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                    @foreach($availableCoupons as $c)
                                    <span onclick="window.fillCoupon('{{ $c->code }}')"
                                          style="border:1px dashed #c8a96e;border-radius:4px;
                                                 padding:4px 10px;cursor:pointer;font-size:12px;
                                                 background:#fdfaf5;">
                                        <strong style="color:#c8a96e;">{{ $c->code }}</strong>
                                        <span style="color:#999;">
                                            — {{ $c->type === 'percent' ? $c->value.'% OFF' : 'LKR '.number_format($c->value,2).' OFF' }}
                                            @if($c->min_order > 0) · Min LKR {{ number_format($c->min_order,0) }}@endif
                                            @if($c->expires_at) · Exp {{ $c->expires_at->format('d M') }}@endif
                                        </span>
                                        <span onclick="event.stopPropagation();copyCode('{{ $c->code }}')"
                                              style="margin-left:4px;color:#c8a96e;font-size:10px;
                                                     border:1px solid #c8a96e;padding:1px 5px;border-radius:3px;">
                                            Copy
                                        </span>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Coupon Input or Applied Badge --}}
                            @if($couponObj)
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:8px;">
                                <span style="background:#f0fdf4;border:1px solid #86efac;border-radius:4px;
                                             padding:6px 14px;font-size:13px;color:#166534;">
                                    ✓ <strong>{{ $couponObj->code }}</strong> applied —
                                    {{ $couponObj->type === 'percent' ? $couponObj->value.'% off' : 'LKR '.number_format($couponObj->value,2).' off' }}
                                </span>
                                <button id="remove-coupon-btn"
                                        style="background:none;border:1px solid #fca5a5;color:#dc2626;
                                               padding:5px 12px;border-radius:4px;cursor:pointer;font-size:12px;">
                                    ✕ Remove
                                </button>
                            </div>
                            @else
                            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
                                <input type="text" id="cart-coupon-input" placeholder="Coupon code"
                                       style="flex:1;min-width:180px;" />
                                <button id="cart-apply-coupon-btn"
                                        style="background:#333;color:#fff;border:none;
                                               padding:8px 18px;cursor:pointer;font-size:13px;">
                                    Apply Coupon
                                </button>
                            </div>
                            <span id="coupon-msg" style="display:none;font-size:13px;margin-top:6px;"></span>
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT: Cart Totals --}}
                    <div class="col-lg-4 col-md-5">
                        <div class="cart_totals">
                            <h2>Cart Totals</h2>
                            <table>
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal</th>
                                        <td><span class="amount">LKR {{ number_format($total,2) }}</span></td>
                                    </tr>
                                    @if($discount > 0)
                                    <tr>
                                        <th>Discount <small style="color:#999;">({{ $couponObj->code }})</small></th>
                                        <td><span style="color:#16a34a;font-weight:600;">
                                            - LKR {{ number_format($discount,2) }}
                                        </span></td>
                                    </tr>
                                    @endif
                                    <tr class="shipping">
                                        <th>Shipping</th>
                                        <td>
                                            <ul id="shipping_method">
                                                <li><input type="radio" checked /><label>Free Shipping</label></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td><strong><span class="amount">LKR {{ number_format($finalTotal,2) }}</span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="wc-proceed-to-checkout">
                                <a href="{{ route('checkout.index') }}">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <h3>Your cart is empty!</h3>
                <a href="{{ route('shop') }}" class="btn btn-default login-btn mt-3">Continue Shopping</a>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30">
                <div class="contuct-info text-center">
                    <h4>Sign up for news &amp; offers!</h4>
                    <p>You may safely unsubscribe at any time</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 offset-lg-1">
                <div class="search-box">
                    <form action="#">
                        <input type="email" placeholder="Enter your email address" />
                        <button><span class="lnr lnr-envelope"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Apply coupon
    $('#cart-apply-coupon-btn').on('click', function() {
        var code = $('#cart-coupon-input').val().trim();
        if (!code) return;
        $.post('/coupon/apply', { code: code, _token: '{{ csrf_token() }}' }, function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                location.reload();
            }
        }).fail(function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid coupon';
            $('#coupon-msg').text('✗ ' + msg).css('color','#dc2626').show();
            showToast(msg, 'error');
        });
    });

    // Remove coupon
    $('#remove-coupon-btn').on('click', function() {
        $.post('/coupon/remove', { _token: '{{ csrf_token() }}' }, function() {
            showToast('Coupon removed', 'success');
            location.reload();
        });
    });

    // Enter key
    $('#cart-coupon-input').on('keypress', function(e) {
        if (e.which === 13) $('#cart-apply-coupon-btn').trigger('click');
    });
});

window.fillCoupon = function(code) {
    $('#cart-coupon-input').val(code);
    $('#cart-apply-coupon-btn').trigger('click');
};

window.copyCode = function(code) {
    navigator.clipboard.writeText(code).then(function() {
        showToast('Code "' + code + '" copied!', 'success');
    }).catch(function() {
        var el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast('Code "' + code + '" copied!', 'success');
    });
};
</script>
@endpush