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
                    <h3 class="grand-table-heading">
                        Order #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <a href="{{ route('orders.index') }}" class="grand-btn">
                        <i class="fa fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>

        {{-- ── ORDER TRACKING TIMELINE ─────────────────── --}}
        @php
            $steps = [
                'pending'    => ['icon' => 'fa-clock-o',       'label' => 'Order Placed'],
                'confirmed'  => ['icon' => 'fa-check-circle',  'label' => 'Confirmed'],
                'processing' => ['icon' => 'fa-cogs',          'label' => 'Processing'],
                'shipped'    => ['icon' => 'fa-truck',         'label' => 'Shipped'],
                'delivered'  => ['icon' => 'fa-home',          'label' => 'Delivered'],
                'completed'  => ['icon' => 'fa-trophy',        'label' => 'Completed'],
            ];

            $statusOrder = ['pending','confirmed','processing','shipped','delivered','completed'];
            $currentIdx  = array_search($order->status, $statusOrder);
            $isCancelled = $order->status === 'cancelled';

            // Progress bar width
            $progressPct = $isCancelled ? 0 : ($currentIdx / (count($statusOrder) - 1)) * 100;
        @endphp

        @if(!$isCancelled)
        <div class="order-tracking-section">
            <h4><i class="fa fa-map-marker"></i> Order Tracking</h4>
            <div style="position:relative;">
                <div class="tracking-timeline">

                    {{-- Progress bar fill --}}
                    <div class="tracking-progress-bar" style="width:calc({{ $progressPct }}% - 80px);"></div>

                    @foreach($steps as $key => $step)
                    @php
                        $stepIdx   = array_search($key, $statusOrder);
                        $isActive  = $key === $order->status;
                        $isDone    = $stepIdx < $currentIdx;
                        $stepClass = $isActive ? 'active' : ($isDone ? 'completed' : '');
                    @endphp
                    <div class="tracking-step {{ $stepClass }}">
                        <div class="tracking-icon">
                            @if($isDone)
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa {{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="tracking-label">{{ $step['label'] }}</div>
                        @if($isActive)
                        <div class="tracking-date">
                            {{ $order->updated_at->format('d M, h:i A') }}
                        </div>
                        @elseif($isDone)
                        <div class="tracking-date">Done</div>
                        @endif
                    </div>
                    @endforeach

                </div>
            </div>

            {{-- Status Message --}}
            <div style="margin-top:20px;padding:12px 16px;background:#f9f9f9;
                        border-radius:6px;border-left:4px solid #c8a96e;">
                @if($order->status === 'pending')
                    <i class="fa fa-clock-o" style="color:#f39c12;"></i>
                    <strong>Order Received</strong> — We've received your order and will confirm it shortly.
                @elseif($order->status === 'confirmed')
                    <i class="fa fa-check-circle" style="color:#1abc9c;"></i>
                    <strong>Order Confirmed</strong> — Your order has been confirmed and will be processed soon.
                @elseif($order->status === 'processing')
                    <i class="fa fa-cogs" style="color:#9b59b6;"></i>
                    <strong>Being Prepared</strong> — Your items are being carefully prepared for shipment.
                @elseif($order->status === 'shipped')
                    <i class="fa fa-truck" style="color:#3498db;"></i>
                    <strong>On the Way!</strong> — Your order has been shipped and is on its way to you.
                @elseif($order->status === 'delivered')
                    <i class="fa fa-home" style="color:#27ae60;"></i>
                    <strong>Delivered</strong> — Your order has been delivered. Please confirm receipt below.
                @elseif($order->status === 'completed')
                    <i class="fa fa-trophy" style="color:#2ecc71;"></i>
                    <strong>Completed</strong> — Thank you for shopping with Grand Furniture!
                @endif
            </div>

            {{-- Mark as Received --}}
            @if(in_array($order->status, ['delivered', 'shipped', 'processing']))
            <form action="{{ route('orders.confirm', $order->id) }}" method="POST" style="margin-top:12px;">
                @csrf
                <button type="submit" class="confirm-received-btn"
                        onclick="return confirm('Confirm you have received this order?')">
                    <i class="fa fa-check"></i> Mark as Received
                </button>
            </form>
            @endif
        </div>

        @else
        {{-- Cancelled --}}
        <div class="order-tracking-section">
            <div style="text-align:center;padding:20px;">
                <i class="fa fa-times-circle" style="font-size:48px;color:#e74c3c;"></i>
                <h4 style="color:#e74c3c;margin-top:12px;">Order Cancelled</h4>
                <p class="text-muted">This order has been cancelled.</p>
            </div>
        </div>
        @endif

        <div class="row">
            {{-- Order Info --}}
            <div class="col-md-6 mb-4">
                <div class="grand-card">
                    <h4 class="grand-card-title">
                        <i class="fa fa-info-circle"></i>Order Information
                    </h4>
                    <table class="w-100 order-info-table">
                        <tr>
                            <td class="order-info-label">Order ID</td>
                            <td><strong>#GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Order Date</td>
                            <td>{{ $order->created_at->format('F d, Y \a\t h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="order-info-label">Status</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
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
                        <i class="fa fa-shopping-bag"></i>Order Items ({{ $order->items->count() }})
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
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            {{ $item->product->name }}
                                        </a>
                                        @if($item->color)
                                        <br><small class="text-muted">Color: {{ $item->color->name }}</small>
                                        @endif
                                        @else
                                        <span class="text-muted">Product unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                        <img src="{{ $item->product->image_url }}"
                                             style="width:60px;height:60px;object-fit:cover;border-radius:4px;"
                                             alt="{{ $item->product->name }}" />
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
            <a href="{{ route('orders.invoice', $order->id) }}"
               class="btn btn-default login-btn"
               style="background:#c8a96e;color:#fff;"
               target="_blank">
                <i class="fa fa-download"></i> Download Invoice
            </a>
            <a href="{{ route('shop') }}" class="btn btn-default login-btn grand-btn-gold">
                <i class="fa fa-shopping-bag"></i> Continue Shopping
            </a>
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