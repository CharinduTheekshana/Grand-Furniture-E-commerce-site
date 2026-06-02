@extends('layouts.admin')
@section('title', 'Order #GF-' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Order Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active">#GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Order ID bar --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-20">
            <div class="card-body d-flex flex-wrap gap-25 justify-content-between">
                <div>
                    <h4>Order #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h4>
                    <span class="text-muted">{{ $order->created_at->format('M d, Y, h:i A') }}</span>
                </div>
                <div class="d-flex-items gap-5">
                    @php
                        $cls = match($order->status) {
                            'pending'    => 'bg-warning-transparent text-warning',
                            'confirmed'  => 'bg-info-transparent text-info',
                            'processing' => 'bg-primary-transparent text-primary',
                            'shipped'    => 'bg-info-transparent text-info',
                            'delivered','completed' => 'bg-success-transparent text-success',
                            'cancelled'  => 'bg-danger-transparent text-danger',
                            default      => 'bg-secondary-transparent text-secondary',
                        };
                    @endphp
                    <span class="badge {{ $cls }} fs-14 py-8 px-16">{{ ucfirst($order->status) }}</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- LEFT — Order Items + Timeline --}}
    <div class="col-xl-8">

        {{-- Order Items --}}
        <div class="card mb-20">
            <div class="card-header border-bottom-0">
                <h4>Order Items</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="w-50">Product</th>
                                <th>Price (LKR)</th>
                                <th>Qty</th>
                                <th class="text-end">Total (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex-items gap-10">
                                        @if($item->product && $item->product->image)
                                        <div class="avatar avatar-md">
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                 class="radius-6"
                                                 style="width:45px;height:45px;object-fit:cover;">
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-5 lh-1">{{ $item->product->name ?? 'Deleted Product' }}</h6>
                                            <span class="fs-12 text-muted lh-1">
                                                {{ $item->product->category->name ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-medium">{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <table class="table w-100 mb-0">
                                        <tbody>
                                            <tr>
                                                <td>Subtotal:</td>
                                                <td class="text-end">LKR {{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Shipping:</td>
                                                <td class="text-end text-success">Free</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <th class="text-end text-primary fw-7">LKR {{ number_format($order->total, 2) }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Order Timeline --}}
        <div class="card">
            <div class="card-header">
                <h4>Order Timeline</h4>
            </div>
            <div class="card-body pt-15">
                <div class="timeline">
                    @php
                        $steps = [
                            'pending'    => ['Order Received', 'We\'ve received your order successfully'],
                            'confirmed'  => ['Confirmed', 'Your order has been confirmed'],
                            'processing' => ['Processing', 'Your order is being prepared'],
                            'shipped'    => ['Shipped', 'Your package has left our warehouse'],
                            'delivered'  => ['Delivered', 'Package delivered successfully'],
                            'completed'  => ['Completed', 'Order completed'],
                            'cancelled'  => ['Cancelled', 'Order was cancelled'],
                        ];
                        $statuses = array_keys($steps);
                        $currentIndex = array_search($order->status, $statuses);
                    @endphp
                    @foreach($steps as $key => $step)
                    @php $idx = array_search($key, $statuses); @endphp
                    <div class="timeline-item {{ $idx > $currentIndex ? 'pending' : '' }}">
                        <div class="timeline-content d-flex-between gap-25">
                            <div class="info">
                                <h6 class="mb-5">{{ $step[0] }}</h6>
                                <p class="text-muted">{{ $step[1] }}</p>
                            </div>
                            <div class="date fs-14 text-nowrap {{ $idx <= $currentIndex ? 'text-primary' : 'text-muted' }}">
                                @if($idx <= $currentIndex)
                                    {{ $order->updated_at->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT — Customer + Address + Update Status --}}
    <div class="col-xl-4">

        {{-- Customer Details --}}
        <div class="card mb-20">
            <div class="card-header">
                <h4>Customer Details</h4>
            </div>
            <div class="card-body pt-15">
                <div class="d-flex-items flex-wrap gap-10 mb-15">
                    <div class="avatar avatar-lg bg-primary-transparent text-primary fw-7"
                         style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;">
                        {{ strtoupper(substr($order->name, 0, 2)) }}
                    </div>
                    <div>
                        <h5>{{ $order->name }}</h5>
                        <p class="mb-0 text-muted">{{ $order->email }}</p>
                        <p class="mb-0 text-muted">{{ $order->phone ?? '—' }}</p>
                    </div>
                </div>
                <div class="d-flex-items flex-wrap gap-25">
                    <div class="d-flex-items gap-5">
                        <span class="avatar avatar-md bg-primary-transparent text-primary radius-100">
                            <i class="ri-file-list-3-line fs-22"></i>
                        </span>
                        <div>
                            <span class="d-block fs-14 text-muted">Total Orders</span>
                            <h6>{{ $order->user ? $order->user->orders->count() : '—' }}</h6>
                        </div>
                    </div>
                    <div class="d-flex-items gap-5">
                        <span class="avatar avatar-md bg-info-transparent text-info radius-100">
                            <i class="ri-wallet-3-line fs-22"></i>
                        </span>
                        <div>
                            <span class="d-block fs-14 text-muted">Total Spent</span>
                            <h6>LKR {{ $order->user ? number_format($order->user->orders->sum('total'), 2) : '—' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="card mb-20">
            <div class="card-header">
                <h4>Delivery Address</h4>
            </div>
            <div class="card-body pt-15">
                <h6 class="mb-10 text-primary">
                    <i class="ri-truck-line me-1"></i> Shipping Address
                </h6>
                <p class="mb-1 fw-medium">{{ $order->name }}</p>
                <p class="mb-1 text-muted">{{ $order->address ?? '—' }}</p>
                <p class="mb-0 text-muted">Phone: {{ $order->phone ?? '—' }}</p>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card">
            <div class="card-header">
                <h4>Update Order Status</h4>
            </div>
            <div class="card-body pt-15">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-15">
                        <label class="form-label">Current Status</label><br>
                        <span class="badge {{ $cls }} fs-13">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="mb-15">
                        <label class="form-label">Change to</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','confirmed','processing','shipped','delivered','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-refresh-line me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
