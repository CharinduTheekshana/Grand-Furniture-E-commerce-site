@extends('layouts.app')
@section('title', 'Checkout - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Checkout</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="entry-header-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="entry-header">
                    <h1 class="entry-title">Checkout</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="coupon-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="coupon-accordion">
                    <h3>Returning customer? <span id="showlogin">Click here to login</span></h3>
                    <div id="checkout-login" class="coupon-content">
                        <div class="coupon-info">
                            <p class="coupon-text">Already have an account? Please login below.</p>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <p class="form-row-first">
                                    <label>Email <span class="required">*</span></label>
                                    <input type="email" name="email" />
                                </p>
                                <p class="form-row-last">
                                    <label>Password <span class="required">*</span></label>
                                    <input type="password" name="password" />
                                </p>
                                <p class="form-row">
                                    <input type="submit" value="Login" />
                                    <label><input type="checkbox" name="remember"> Remember me</label>
                                </p>
                            </form>
                        </div>
                    </div>
                    <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                    <div id="checkout_coupon" class="coupon-checkout-content">
                        <div class="coupon-info">
                            <form action="#">
                                <p class="checkout-coupon">
                                    <input type="text" placeholder="Coupon code" />
                                    <input type="submit" value="Apply Coupon" />
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-area pb-50">
    <div class="container">
        <form class="row" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <input type="hidden" name="shipping_method" id="shipping_method" value="flat_rate">

            {{-- BILLING DETAILS --}}
            <div class="col-lg-6">
                <div class="checkbox-form">
                    <h3>Billing Details</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="country-select">
                                <label>Country <span class="required">*</span></label>
                                <select name="country">
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="India">India</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Maldives">Maldives</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>First Name <span class="required">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->name) }}" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>Last Name <span class="required">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout-form-list">
                                <label>Company Name</label>
                                <input type="text" name="company" value="{{ old('company') }}" />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout-form-list">
                                <label>Address <span class="required">*</span></label>
                                <input type="text" name="address" placeholder="Street address" value="{{ old('address') }}" required />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout-form-list">
                                <input type="text" name="address2" placeholder="Apartment, suite, unit etc. (optional)" value="{{ old('address2') }}" />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="checkout-form-list">
                                <label>Town / City <span class="required">*</span></label>
                                <input type="text" name="city" placeholder="Town / City" value="{{ old('city') }}" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>State / Province <span class="required">*</span></label>
                                <input type="text" name="state" value="{{ old('state') }}" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>Postcode / Zip <span class="required">*</span></label>
                                <input type="text" name="postcode" placeholder="Postcode / Zip" value="{{ old('postcode') }}" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>Email Address <span class="required">*</span></label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="checkout-form-list">
                                <label>Phone <span class="required">*</span></label>
                                <input type="text" name="phone" placeholder="Phone number" value="{{ old('phone') }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="different-address">
                        <div class="ship-different-title">
                            <h3>
                                <label>Ship to a different address?</label>
                                <input id="ship-box" type="checkbox" />
                            </h3>
                        </div>
                        <div id="ship-box-info" class="row" style="display:none;">
                            <div class="col-lg-12">
                                <div class="country-select">
                                    <label>Country <span class="required">*</span></label>
                                    <select name="ship_country">
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="India">India</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout-form-list">
                                    <label>First Name <span class="required">*</span></label>
                                    <input type="text" name="ship_first_name" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout-form-list">
                                    <label>Last Name <span class="required">*</span></label>
                                    <input type="text" name="ship_last_name" />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout-form-list">
                                    <label>Address <span class="required">*</span></label>
                                    <input type="text" name="ship_address" placeholder="Street address" />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout-form-list">
                                    <label>Town / City <span class="required">*</span></label>
                                    <input type="text" name="ship_city" placeholder="Town / City" />
                                </div>
                            </div>
                        </div>

                        <div class="order-notes">
                            <div class="checkout-form-list">
                                <label>Order Notes</label>
                                <textarea name="notes" id="checkout-mess" cols="30" rows="10"
                                    placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- YOUR ORDER --}}
            <div class="col-lg-6">
                <div class="your-order">
                    <h3>Your order</h3>
                    <div class="your-order-table table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product-name">Product</th>
                                    <th class="product-total">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @forelse($cartItems as $item)
                                @php
                                    $price = $item->product->sale_price ?? $item->product->price;
                                    $lineTotal = $price * $item->quantity;
                                    $subtotal += $lineTotal;
                                @endphp
                                <tr class="cart_item">
                                    <td class="product-name">
                                        {{ $item->product->name }}
                                        <strong class="product-quantity"> × {{ $item->quantity }}</strong>
                                        @if($item->color)
                                        <br><small class="text-muted">Color: {{ $item->color->name }}</small>
                                        @endif
                                        <br><small class="text-muted">Unit: LKR {{ number_format($price, 2) }}</small>
                                    </td>
                                    <td class="product-total">
                                        <span class="amount">LKR {{ number_format($lineTotal, 2) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center">Cart is empty</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th>Cart Subtotal</th>
                                    <td><span class="amount" id="cart-subtotal" data-subtotal="{{ $subtotal }}">LKR {{ number_format($subtotal, 2) }}</span></td>
                                </tr>
                                <tr class="shipping">
                                    <th>Shipping</th>
                                    <td>
                                        <ul>
                                            <li>
                                                <input type="radio" name="shipping_option" value="flat_rate" checked>
                                                <label>Flat Rate: <span class="amount">LKR 1,500.00</span></label>
                                            </li>
                                            <li>
                                                <input type="radio" name="shipping_option" value="free_shipping">
                                                <label>Free Shipping:</label>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Order Total</th>
                                    <td><strong><span class="amount" id="order-total">LKR {{ number_format($subtotal + 1500, 2) }}</span></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- PAYMENT METHODS --}}
                    <div class="payment-method">
                        <div class="panel-group" id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h4 class="card-title">
                                        <a data-bs-toggle="collapse" href="#collapseOne">Direct Bank Transfer</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show">
                                    <div class="card-body payment-content">
                                        Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be shipped until the funds have cleared in our account.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h4 class="card-title">
                                        <a data-bs-toggle="collapse" href="#collapseTwo">Cheque Payment</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse">
                                    <div class="card-body payment-content">
                                        Please send your cheque to our store address.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h4 class="card-title panel-img">
                                        <a data-bs-toggle="collapse" href="#collapseThree">
                                            PayPal <img src="{{ asset('assets/images/payment_c.png') }}" alt="" style="max-height:65px;" />
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="collapse">
                                    <div class="card-body payment-content">
                                        Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-button-payment">
                            <input type="submit" value="Place order" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var subtotal = parseFloat($('#cart-subtotal').data('subtotal')) || 0;

    function updateTotal() {
        var shipping = $('input[name="shipping_option"]:checked').val();
        var fee = shipping === 'free_shipping' ? 0 : 1500;
        $('#shipping_method').val(shipping);
        $('#order-total').text('LKR ' + (subtotal + fee).toLocaleString('en-US', {minimumFractionDigits:2}));
    }

    $('input[name="shipping_option"]').on('change', updateTotal);
    updateTotal();

    // Ship to different address toggle
    $('#ship-box').on('change', function() {
        $('#ship-box-info').toggle(this.checked);
    });

    // Login accordion
    $('#showlogin').on('click', function() {
        $('#checkout-login').slideToggle();
    });

    // Coupon accordion
    $('#showcoupon').on('click', function() {
        $('#checkout_coupon').slideToggle();
    });
});
</script>
@endpush