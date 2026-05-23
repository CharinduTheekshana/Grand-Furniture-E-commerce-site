@extends('layouts.app')
@section('title', 'Order #' . $order->id . ' - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Order Details</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="cart-main-area ptb-80">
    <div class="container">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="grand-table-header">
                    <h3 class="grand-table-heading">Order #{{ $order->id }}</h3>
                    <a href="{{ route('orders.index') }}" class="grand-btn">
                        <i class="fa fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Order Info --}}
            <div class="col-md-6 mb-4">
                <div class="grand-card">
                    <h4 class="grand-card-title">
                        <i class="fa fa-info-circle"></i>Order Information
                    </h4>
                    <table class="w-100 order-info-table">
                        <tr>
                            <td class="order-info-label">Order Date</td>
                            <td>{{ $order->created_at->format('F d, Y \a\t h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Status</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Total</td>
                            <td><strong>LKR {{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="col-md-6 mb-4">
                <div class="grand-card">
                    <h4 class="grand-card-title">
                        <i class="fa fa-map-marker"></i>Shipping Information
                    </h4>
                    <table class="w-100 order-info-table">
                        <tr>
                            <td class="order-info-label">Name</td>
                            <td>{{ $order->name }}</td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Address</td>
                            <td>{{ $order->address }}</td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Email</td>
                            <td>{{ $order->email }}</td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Phone</td>
                            <td>{{ $order->phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="grand-card">
                    <h4 class="grand-card-title">
                        <i class="fa fa-shopping-bag"></i>Order Items
                    </h4>
                    <div class="table-content table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                @php $lineTotal = $item->price * $item->quantity; @endphp
                                <tr>
                                    <td>
                                        @if($item->product)
                                        <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        @else
                                        <span class="text-muted">Product unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                        <img src="{{ $item->product->image_url }}" style="width:60px;height:60px;object-fit:cover;" alt="{{ $item->product->name }}" />
                                        @endif
                                    </td>
                                    <td>LKR {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>LKR {{ number_format($lineTotal, 2) }}</strong></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center">No items found.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end pe-3"><strong>Order Total:</strong></td>
                                    <td><strong class="payment-total">LKR {{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="grand-actions">
            <a href="{{ route('orders.index') }}" class="btn btn-default login-btn">
                <i class="fa fa-list"></i> My Orders
            </a>
            <a href="{{ route('shop') }}" class="btn btn-default login-btn grand-btn-gold">
                <i class="fa fa-shopping-bag"></i> Continue Shopping
            </a>
            @if($order->status === 'processing')
            <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-default login-btn grand-btn-green"
                    onclick="return confirm('Confirm order received?')">
                    <i class="fa fa-check"></i> Confirm Received
                </button>
            </form>
            @endif
        </div>

    </div>
</div>

<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div>
            <div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address"/><button><span class="lnr lnr-envelope"></span></button></form></div></div>
        </div>
    </div>
</div>

@endsection