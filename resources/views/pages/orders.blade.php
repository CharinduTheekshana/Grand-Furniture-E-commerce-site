@extends('layouts.app')
@section('title', 'My Orders - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>My Orders</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="cart-main-area ptb-80">
    <div class="container">

        @if($orders->count() > 0)

        {{-- Order Summary Stats --}}
        <div class="row mb-4">
            @php
                $pendingCount   = $orders->whereIn('status', ['pending','confirmed','processing'])->count();
                $shippedCount   = $orders->whereIn('status', ['shipped','delivered'])->count();
                $completedCount = $orders->where('status', 'completed')->count();
            @endphp
            <div class="col-md-4 mb-3">
                <div style="background:#fff8f0;border:1px solid #f0dfc0;border-radius:8px;
                            padding:16px;text-align:center;">
                    <div style="font-size:28px;font-weight:700;color:#c8a96e;">{{ $pendingCount }}</div>
                    <div style="font-size:13px;color:#999;">Active Orders</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div style="background:#f0f9ff;border:1px solid #bae0fd;border-radius:8px;
                            padding:16px;text-align:center;">
                    <div style="font-size:28px;font-weight:700;color:#3498db;">{{ $shippedCount }}</div>
                    <div style="font-size:13px;color:#999;">In Transit</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
                            padding:16px;text-align:center;">
                    <div style="font-size:28px;font-weight:700;color:#2ecc71;">{{ $completedCount }}</div>
                    <div style="font-size:13px;color:#999;">Completed</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="grand-card">
                    <h4 class="grand-card-title">
                        <i class="fa fa-shopping-bag"></i>Order History
                    </h4>
                    <div class="table-content table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span style="background:#f0f0f0;padding:2px 8px;
                                                     border-radius:10px;font-size:12px;">
                                            {{ $order->items->count() }} item{{ $order->items->count() != 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td><strong>LKR {{ number_format($order->total, 2) }}</strong></td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        @if(in_array($order->status, ['delivered']))
                                        <br>
                                        <form action="{{ route('orders.confirm', $order->id) }}"
                                              method="POST" class="d-inline" style="margin-top:6px;">
                                            @csrf
                                            <button type="submit" class="confirm-received-btn"
                                                    onclick="return confirm('Confirm order received?')">
                                                <i class="fa fa-check"></i> Mark Received
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->status === 'pending')
                                        <a href="{{ route('payment.show', $order->id) }}"
                                           class="grand-btn grand-btn-gold"
                                           style="font-size:12px;padding:6px 12px;">
                                            <i class="fa fa-credit-card"></i> Pay Now
                                        </a>
                                        @else
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="grand-btn"
                                           style="font-size:12px;padding:6px 12px;">
                                            <i class="fa fa-map-marker"></i> Track Order
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('shop') }}" class="btn btn-default login-btn">
                        <i class="fa fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        @else
        <div class="row">
            <div class="col-12 grand-empty-state">
                <i class="fa fa-shopping-bag grand-empty-icon"></i>
                <h3 class="grand-empty-title">No Orders Yet!</h3>
                <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('shop') }}" class="btn btn-default login-btn">
                    <i class="fa fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        </div>
        @endif

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