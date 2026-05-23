@extends('layouts.app')
@section('title', 'Payment - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Secure Payment</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-area pb-80 pt-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                {{-- Order Summary --}}
                <div class="payment-card">
                    <h3 class="mb-3">Order Summary</h3>
                    <div class="payment-summary-row">
                        <span>Order #</span>
                        <strong>#{{ $order->id }}</strong>
                    </div>
                    <div class="payment-summary-row">
                        <span>Customer</span>
                        <span>{{ $order->name }}</span>
                    </div>
                    <div class="payment-summary-row payment-total">
                        <span>Total</span>
                        <span>LKR {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="checkbox-form">
                    <h3>Card Details</h3>

                    <form action="{{ route('payment.process', $order->id) }}" method="POST" id="payment-form">
                        @csrf

                        <div class="checkout-form-list mb-3">
                            <label>Card Number <span class="required">*</span></label>
                            <input type="text" name="card_number" id="card_number"
                                class="payment-input" placeholder="1234 5678 9012 3456"
                                maxlength="19" required />
                        </div>

                        <div class="checkout-form-list mb-3">
                            <label>Card Holder Name <span class="required">*</span></label>
                            <input type="text" name="card_holder"
                                class="payment-input" placeholder="Name on card"
                                value="{{ $order->name }}" required />
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout-form-list mb-3">
                                    <label>Expiry Date <span class="required">*</span></label>
                                    <input type="text" name="expiry"
                                        class="payment-input" placeholder="MM/YY"
                                        maxlength="5" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout-form-list mb-3">
                                    <label>CVV <span class="required">*</span></label>
                                    <input type="text" name="cvv"
                                        class="payment-input" placeholder="123"
                                        maxlength="3" required />
                                </div>
                            </div>
                        </div>

                        <div class="payment-ssl-note mb-4">
                            <i class="fa fa-lock"></i>
                            <span>Secured by SSL encryption. Your payment info is safe.</span>
                        </div>

                        <button type="submit" id="pay-btn" class="pay-button">
                            <i class="fa fa-lock"></i> Pay LKR {{ number_format($order->total, 2) }}
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('orders.index') }}" class="text-muted small">
                                <i class="fa fa-arrow-left"></i> Cancel & Back to Orders
                            </a>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <img src="{{ asset('assets/images/payment.png') }}" alt="Payment Methods" style="max-height:30px;" />
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$('#card_number').on('input', function() {
    var val = $(this).val().replace(/\D/g, '').substring(0, 16);
    $(this).val(val.match(/.{1,4}/g) ? val.match(/.{1,4}/g).join(' ') : val);
});
$('input[name="expiry"]').on('input', function() {
    var val = $(this).val().replace(/\D/g, '').substring(0, 4);
    if (val.length >= 3) val = val.substring(0, 2) + '/' + val.substring(2);
    $(this).val(val);
});
$('input[name="cvv"]').on('input', function() {
    $(this).val($(this).val().replace(/\D/g, '').substring(0, 3));
});
$('#payment-form').on('submit', function() {
    $('#pay-btn').html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
});
</script>
@endpush