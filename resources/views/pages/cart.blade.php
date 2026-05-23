@extends('layouts.app')
@section('title','Cart - Grand Furniture')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Cart</h3></div></div></div></div></div>
<div class="cart-main-area ptb-40">
    <div class="container">
        @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-12">
                <div class="table-content table-responsive">
                    <table>
                        <thead><tr><th class="product-thumbnail">Image</th><th class="product-name">Product</th><th class="product-price">Price</th><th class="product-quantity">Quantity</th><th class="product-subtotal">Total</th><th class="product-remove">Remove</th></tr></thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td class="product-thumbnail"><a href="{{ route('product.show',$item->product->slug) }}"><img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" /></a></td>
                                <td class="product-name"><a href="{{ route('product.show',$item->product->slug) }}">{{ $item->product->name }}</a></td>
                                <td class="product-price"><span class="amount">LKR {{ number_format($item->product->price,2) }}</span></td>
                                <td class="product-quantity"><form action="{{ route('cart.update',$item->id) }}" method="POST">@csrf @method('PATCH')<input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()"></form></td>
                                <td class="product-subtotal">LKR {{ number_format($item->product->price * $item->quantity,2) }}</td>
                                <td class="product-remove"><form action="{{ route('cart.remove',$item->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" style="background:none;border:none;cursor:pointer;"><i class="fa fa-times"></i></button></form></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-7">
                        <div class="buttons-cart"><a href="{{ route('shop') }}">Continue Shopping</a></div>
                        <div class="coupon"><h3>Coupon</h3><p>Enter your coupon code if you have one.</p><input type="text" placeholder="Coupon code" /><input type="submit" value="Apply Coupon" /></div>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="cart_totals"><h2>Cart Totals</h2>
                            <table><tbody>
                                <tr class="cart-subtotal"><th>Subtotal</th><td><span class="amount">LKR {{ number_format($total,2) }}</span></td></tr>
                                <tr class="shipping"><th>Shipping</th><td><ul id="shipping_method"><li><input type="radio" checked /><label>Free Shipping</label></li></ul></td></tr>
                                <tr class="order-total"><th>Total</th><td><strong><span class="amount">LKR {{ number_format($total,2) }}</span></strong></td></tr>
                            </tbody></table>
                            <div class="wc-proceed-to-checkout"><a href="{{ route('checkout.index') }}">Proceed to Checkout</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row"><div class="col-12 text-center py-5"><h3>Your cart is empty!</h3><a href="{{ route('shop') }}" class="btn btn-default login-btn mt-3">Continue Shopping</a></div></div>
        @endif
    </div>
</div>
<div class="contact-area ptb-40"><div class="container"><div class="row"><div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div><div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address" /><button><span class="lnr lnr-envelope"></span></button></form></div></div></div></div></div>
@endsection
