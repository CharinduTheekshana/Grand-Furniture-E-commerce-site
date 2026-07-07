@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
.vendor-box-wrap { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
.vendor-box { border: 1px solid var(--border-color, #e9e9e9); border-radius: 10px; padding: 16px; overflow: hidden; }
.table:not(.text-nowrap) td, .table:not(.text-nowrap) th { white-space: normal !important; word-break: break-word; }
.table-responsive { overflow-x: auto !important; -webkit-overflow-scrolling: touch; }
.table.text-nowrap td, .table.text-nowrap th { white-space: nowrap !important; }
.transactions-list { list-style: none; padding: 0; margin: 0; }
.trendingProduct { border-radius: 12px; overflow: hidden; }
.card-slide-wrapper { position: relative; min-height: 200px; }
.card-slide-thumb img { height: 200px; object-fit: cover; width: 100%; }
.card-slide-bottom { position: absolute; bottom: 0; left: 0; right: 0; padding: 16px; background: linear-gradient(transparent, rgba(0,0,0,0.7)); }
.bd-price .current-price { color: #fff; font-size: 16px; font-weight: 600; }
.bd-price .old-price { color: rgba(255,255,255,0.6); text-decoration: line-through; margin-left: 8px; font-size: 13px; }
.card-slide-top { padding: 16px 16px 0; }
</style>
@endpush

@section('content')

{{-- Page Title --}}
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Ecommerce Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row overflow-hidden">

{{-- ── Stat Cards ──────────────────────────────────── --}}
<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body mini-card-body d-flex align-center gap-16">
            <div class="avatar avatar-xl bg-primary-transparent text-primary">
                <i class="ri-shopping-bag-3-line fs-42"></i>
            </div>
            <div class="card-content">
                <span class="d-block fs-16 mb-5">Total Orders</span>
                <h2 class="mb-5" id="admin-order-count">{{ $orderCount }}</h2>
                <span class="text-success">+1.24%<i class="ri-arrow-up-line ml-5 d-inline-block"></i></span>
                <span class="fs-12 text-muted ml-5">This week</span>
            </div>
        </div>
    </div>
</div>
<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body mini-card-body d-flex align-center gap-16">
            <div class="avatar avatar-xl bg-info-transparent text-info">
                <i class="ri-user-line fs-42"></i>
            </div>
            <div class="card-content">
                <span class="d-block fs-16 mb-5">Customers</span>
                <h2 class="mb-5">{{ $userCount }}</h2>
                <span class="text-success">+0.87%<i class="ri-arrow-up-line ml-5 d-inline-block"></i></span>
                <span class="fs-12 text-muted ml-5">This week</span>
            </div>
        </div>
    </div>
</div>
<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body mini-card-body d-flex align-center gap-16">
            <div class="avatar avatar-xl bg-danger-transparent text-danger">
                <i class="ri-box-3-line fs-42"></i>
            </div>
            <div class="card-content">
                <span class="d-block fs-16 mb-5">Available Products</span>
                <h2 class="mb-5">{{ $productCount }}</h2>
                <a href="{{ route('admin.products.index') }}" class="fs-12 text-primary">Manage</a>
            </div>
        </div>
    </div>
</div>
<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body mini-card-body d-flex align-center gap-16">
            <div class="avatar avatar-xl bg-success-transparent text-success">
                <i class="ri-wallet-3-line fs-42"></i>
            </div>
            <div class="card-content">
                <span class="d-block fs-16 mb-5">Total Revenue</span>
                <h2 class="mb-5">LKR {{ number_format($totalRevenue) }}</h2>
                <span class="text-success">+2.05%<i class="ri-arrow-up-line ml-5 d-inline-block"></i></span>
                <span class="fs-12 text-muted ml-5">This week</span>
            </div>
        </div>
    </div>
</div>


<!-- {{-- ── Active Offers Widget ── --}}
<div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body mini-card-body d-flex align-center gap-16">
            <div class="avatar avatar-xl bg-warning-transparent text-warning">
                <i class="ri-price-tag-3-line fs-42"></i>
            </div>
            <div class="card-content">
                <span class="d-block fs-16 mb-5">Active Offers</span>
                <h2 class="mb-5">{{ $activeOffers ?? 0 }}</h2>
                <span class="text-danger fs-12">{{ $expiredOffers ?? 0 }} expired</span>
            </div>
        </div>
    </div>
</div> -->

{{-- ── Revenue Report + Sales by Locations + Trending Product ── --}}
<div class="col-xxl-6 col-xl-12" style="display:flex;flex-direction:column;">
    <div class="card" style="flex:1;">
        <div class="card-header justify-between">
            <h4>Revenue Report</h4>
            <div class="card-dropdown">
                <div class="dropdown">
                    <a class="card-dropdown-icon" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">This Week</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
                        <a class="dropdown-item" href="javascript:void(0);">This Month</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-15">
            <div id="order-status"></div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6" style="display:flex;flex-direction:column;">
    <div class="card" style="flex:1;">
        <div class="card-header justify-between">
            <h4>Sales by Locations</h4>
        </div>
        <div class="card-body pt-15">
            <div id="seles-countries" style="height:160px;"></div>
            <div class="bd-progress-wrapper mt-15">
                @php
                    $provinces = [
                        ['Western', '#4F46E5', 70],
                        ['Central', '#FEBB7B', 55],
                        ['Southern', '#35BE5E', 45],
                        ['Northern', '#93E7FE', 35],
                    ];
                @endphp
                @foreach($provinces as $p)
                <div class="single-progress mb-10">
                    <div class="d-flex-between mb-5">
                        <h6 class="fs-14">{{ $p[0] }}</h6>
                        <span class="progress-number">{{ $p[2] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $p[2] }}%; background:{{ $p[1] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6" style="display:flex;flex-direction:column;">
    <div class="card-carousel p-relative" style="flex:1;">
        <div class="card-slide-top">
            <h4 class="mb-10 text-black">Trending Product</h4>
            <span class="badge bg-label-dark">
                <span class="text-success mr-5">New</span> This Week
            </span>
        </div>
        <div class="swiper trendingProduct p-relative">
            <div class="swiper-wrapper">
                @forelse($trendingProducts as $tp)
                <div class="swiper-slide">
                    <div class="card-slide-wrapper p-relative">
                        <div class="card-slide-thumb">
                            <img src="{{ asset('storage/' . $tp->image) }}"
                                alt="{{ $tp->name }}"
                                style="width:100%;height:180px;object-fit:cover;">
                        </div>
                        <div class="card-slide-bottom">
                            <h5 class="text-white mb-10">
                                <a href="{{ route('product.show', $tp->slug) }}" target="_blank">{{ $tp->name }}</a>
                            </h5>
                            <div class="bd-price">
                                <span class="current-price">LKR {{ number_format($tp->price, 2) }}</span>
                                @if($tp->old_price)
                                <span class="old-price">LKR {{ number_format($tp->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <div class="card-slide-wrapper p-relative" style="background:#f0f0f0;height:180px;display:flex;align-items:center;justify-content:center;">
                        <p class="text-muted">No featured products</p>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="card-slide-pagination tranding">
                <div class="bd-pagination"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Top Level Seller (Top Customers) + Best Selling Products ── --}}
<div class="col-xxl-6 col-xl-12">
    <div class="card">
        <div class="card-header justify-between">
            <h4>Top Level Customers</h4>
        </div>
        <div class="card-body pt-15">
            <div class="card-scrollbar" style="max-height:520px;overflow-y:auto;">
                <div class="vendor-box-wrap">
                    @forelse($topCustomers as $i => $cust)
                    @php
                        $chartIds = ['widgetChartYear','widgetChartYear2','widgetChartYear3','widgetChartYear4','widgetChartYear5'];
                        $colors   = ['#4F46E5','#FEBB7B','#35BE5E','#93E7FE','#F991DC'];
                    @endphp
                    <div class="vendor-box p-relative">
                        <div class="vendor-content">
                            <div class="d-flex-between">
                                <div class="d-flex gap-15">
                                    <div class="vendor-thumb bg-primary-transparent text-primary"
                                         style="width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;">
                                        {{ strtoupper(substr($cust->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-5">{{ $cust->user->name ?? 'Unknown' }}</h5>
                                        <span class="text-body">{{ $cust->user->email ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="vendor-content mb-10">
                                <span class="d-block mb-5">Total Spent</span>
                                <div class="d-flex flex-wrap gap-10">
                                    <h3>LKR {{ number_format($cust->total_spent ?? 0) }}</h3>
                                    <div>
                                        <span class="text-success">{{ $cust->order_count }} orders<i class="ri-arrow-up-line ml-5 d-inline-block"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vendor-chart">
                            <div id="{{ $chartIds[$i] ?? 'chart'.$i }}"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">No customer data yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-6 col-xl-12">
    <div class="card">
        <div class="card-header justify-between">
            <h4>Best Selling Products</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">View All</a>
        </div>
        <div class="card-body pt-15">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Order</th>
                            <th>Available</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bestSelling as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-10">
                                    @if($item->product && $item->product->image)
                                    <div class="avatar">
                                        <img class="radius-6"
                                             src="{{ asset('storage/' . $item->product->image) }}"
                                             style="width:40px;height:40px;object-fit:cover;">
                                    </div>
                                    @endif
                                    <h6 class="text-heading fw-6">{{ $item->product->name ?? 'Deleted Product' }}</h6>
                                </div>
                            </td>
                            <td class="text-heading">LKR {{ $item->product ? number_format($item->product->price, 2) : '—' }}</td>
                            <td class="text-muted">{{ $item->total_sold }}</td>
                            <td>
                                @if($item->product)
                                    @if($item->product->stock > 0)
                                        <span class="text-muted">{{ $item->product->stock }}</span>
                                    @else
                                        <span class="badge bg-label-danger">Out of Stock</span>
                                    @endif
                                @else —
                                @endif
                            </td>
                            <td class="text-heading">LKR {{ number_format($item->total_revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No sales data yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── Recent Transactions + Top Customer + Top Selling Categories ── --}}
<div class="col-xxl-4 col-xl-6 col-lg-6">
    <div class="card height-equal">
        <div class="card-header justify-between">
            <h4>Recent Transactions</h4>
        </div>
        <div class="card-body pt-15">
            <ul class="transactions-list">
                @forelse($recentOrders->take(6) as $order)
                @php
                    $icon = 'ri-bank-card-line';
                    $amtClass = in_array($order->status, ['completed','delivered','shipped']) ? 'text-success' : 'text-warning';
                @endphp
                <li class="d-flex-between flex-xxs-wrap gap-15 mb-15">
                    <div class="d-flex align-center">
                        <div class="badge square fs-18 bg-label-primary py-10 mr-10">
                            <i class="{{ $icon }}"></i>
                        </div>
                        <div>
                            <h6 class="fs-14">Order #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h6>
                            <span class="text-muted lh-1">{{ $order->name }} • {{ $order->created_at->format('d M') }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <h6 class="{{ $amtClass }} mb-5">LKR {{ number_format($order->total, 2) }}</h6>
                        <span class="text-body-secondary">{{ ucfirst($order->status) }}</span>
                    </div>
                </li>
                @empty
                <li class="text-center text-muted py-3">No transactions yet</li>
                @endforelse
            </ul>
            <div class="d-flex-between stats-card mt-30">
                <div class="text-center">
                    <h3 class="text-success mb-5">LKR {{ number_format($totalRevenue) }}</h3>
                    <p class="text-muted fs-12">Total Revenue</p>
                </div>
                <div class="text-center">
                    <h3 class="text-primary mb-5">{{ $orderCount }}</h3>
                    <p class="text-muted fs-12">Transactions</p>
                </div>
                <div class="text-center">
                    <h3 class="text-warning mb-5">{{ \App\Models\Order::where('status','pending')->count() }}</h3>
                    <p class="text-muted fs-12">Pending</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-6 col-lg-6">
    <div class="card height-equal">
        <div class="card-header justify-between">
            <h4>Top Customer</h4>
        </div>
        <div class="card-body pt-15">
            <ul>
                @forelse($topCustomers as $cust)
                <li class="d-flex-between mb-15">
                    <div class="d-flex-items gap-10">
                        <div class="avatar avatar-md radius-100 bg-primary-transparent text-primary fw-7">
                            {{ strtoupper(substr($cust->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $cust->user->name ?? 'Unknown' }}</h6>
                            <span class="text-muted">Premium Member</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fs-16 fw-6">{{ $cust->order_count }}</div>
                        <span class="fs-14 text-muted">Order</span>
                    </div>
                </li>
                @empty
                <li class="text-center text-muted py-3">No data yet</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="col-xxl-5 col-xl-12">
    <div class="card height-equal">
        <div class="card-header justify-between">
            <h4>Top Selling Categories</h4>
        </div>
        <div class="card-body pt-15">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Revenue (LKR)</th>
                            <th>Orders</th>
                            <th>Avg. Order</th>
                            <th>Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCategories as $cat)
                        <tr>
                            <td>
                                <h6 class="mb-5">{{ $cat->name }}</h6>
                                <span class="text-muted">{{ $cat->products_count }} products</span>
                            </td>
                            <td class="text-end fw-6">LKR {{ number_format($cat->category_revenue ?? 0, 2) }}</td>
                            <td class="text-end">{{ $cat->category_orders ?? 0 }}</td>
                            <td class="text-end">
                                LKR {{ $cat->category_orders > 0 ? number_format(($cat->category_revenue ?? 0) / $cat->category_orders, 2) : '0.00' }}
                            </td>
                            <td class="text-end">
                                <span class="text-success"><i class="ri-arrow-up-line mr-5"></i> Active</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No categories yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── Stock Alerts ─────────────────────────────── --}}
@if($lowStockProducts->count() > 0)
<div class="row mb-20">
    <div class="col-xl-12">
        <div class="card border-warning" style="border-left: 4px solid #f59e0b;">
            <div class="card-header justify-between">
                <h4 class="text-warning">
                    <i class="ri-alert-line me-1"></i>
                    Stock Alert — {{ $lowStockProducts->count() }} products need attention
                </h4>
                <a href="{{ route('admin.products.index') }}" class="btn btn-warning btn-sm text-white">
                    View All Products
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        @if($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}"
                                             style="width:36px;height:36px;object-fit:cover;border-radius:6px;">
                                        @endif
                                        <span class="fw-medium">{{ $p->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $p->category->name ?? '—' }}</td>
                                <td>
                                    <span class="fw-medium {{ $p->stock == 0 ? 'text-danger' : 'text-warning' }}">
                                        {{ $p->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if($p->stock == 0)
                                        <span class="badge bg-danger-transparent text-danger">Out of Stock</span>
                                    @else
                                        <span class="badge bg-warning-transparent text-warning">Low Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $p) }}"
                                       class="btn-icon btn-info-light" title="Update Stock">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Recent Orders ─────────────────────────────── --}}
<div class="col-xl-12">
    <div class="card">
        <div class="card-header justify-between">
            <h4>Recent Orders</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">View All</a>
        </div>
        <div class="card-body pt-15">
            <div class="table-responsive">
                <table class="table text-nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount (LKR)</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders->take(6) as $order)
                        @php
                            $cls = match($order->status) {
                                'pending'    => 'bg-label-warning',
                                'confirmed'  => 'bg-label-info',
                                'processing' => 'bg-label-primary',
                                'shipped'    => 'bg-label-info',
                                'delivered','completed' => 'bg-label-success',
                                'cancelled'  => 'bg-label-danger',
                                default      => 'bg-label-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="fw-medium text-primary">
                                    #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex-items gap-10">
                                    <div class="avatar avatar-md radius-100 bg-primary-transparent text-primary fw-7">
                                        {{ strtoupper(substr($order->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h6>{{ $order->name }}</h6>
                                        <span class="fs-12 text-muted">{{ $order->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $order->items->count() }}</td>
                            <td class="fw-medium">{{ number_format($order->total, 2) }}</td>
                            <td><span class="badge {{ $cls }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex-items gap-10">
                                    <a class="btn-icon btn-success-light"
                                       href="{{ route('admin.orders.show', $order) }}" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No orders yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>{{-- row end --}}
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/plugins/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/world-merc.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/swiper.min.js') }}"></script>
<script>
$(document).ready(function() {

    // ── Revenue Report Chart ──────────────────────
    var revenueOptions = {
        chart: { height: 352, type: 'line', stacked: false, toolbar: { show: false } },
        series: [
            { name: 'Orders', type: 'area', data: {!! json_encode($ordersData) !!}, color: "#FEBB7B" },
            { name: 'Revenue (LKR)', type: 'column', data: {!! json_encode($revenueData) !!}, color: "#4F46E5" }
        ],
        stroke: { width: [2, 0], curve: 'smooth' },
        plotOptions: { bar: { columnWidth: '45%' } },
        fill: { opacity: [0.2, 1] },
        labels: {!! json_encode($revenueLabels) !!},
        markers: { size: 5 },
        xaxis: { type: 'category', labels: { style: { colors: 'var(--color-body)', fontSize: '12px', fontFamily: 'var(--ff-body)' } } },
        yaxis: { labels: { style: { colors: 'var(--color-body)', fontSize: '12px' } } },
        legend: { position: "top", labels: { colors: "var(--color-body)" } },
        tooltip: { shared: true, intersect: false }
    };
    new ApexCharts(document.querySelector("#order-status"), revenueOptions).render();

    // ── Sales by Location Map ─────────────────────
    if (document.querySelector("#seles-countries")) {
        new jsVectorMap({
            map: "world_merc",
            selector: "#seles-countries",
            zoomOnScroll: false,
            zoomButtons: false,
            markers: [
                { name: "Colombo", coords: [6.9271, 79.8612] },
                { name: "Kandy", coords: [7.2906, 80.6337] },
                { name: "Galle", coords: [6.0535, 80.2210] },
                { name: "Jaffna", coords: [9.6615, 80.0255] },
            ],
            markerStyle: { hover: { stroke: "#DDD", strokeWidth: 3, fill: "#FFF" }, selected: { fill: "#ff525d" } },
            regionStyle: { initial: { stroke: "#e9e9e9", strokeWidth: .15, fill: "var(--gray-3)", fillOpacity: 1 } },
            labels: { markers: { render: function(marker) { return marker.name; } } }
        });
    }

    // ── Trending Products Swiper ──────────────────
    new Swiper(".trendingProduct", {
        pagination: { el: ".bd-pagination", clickable: true }
    });

    // ── Customer Sparkline Charts ─────────────────
    @php $colors = ['#4F46E5','#FEBB7B','#35BE5E','#93E7FE','#F991DC']; @endphp
    @foreach($topCustomers as $i => $cust)
    @php $chartId = ['widgetChartYear','widgetChartYear2','widgetChartYear3','widgetChartYear4','widgetChartYear5'][$i] ?? 'chart'.$i; @endphp
    if (document.querySelector("#{{ $chartId }}")) {
        new ApexCharts(document.querySelector("#{{ $chartId }}"), {
            series: [{ name: 'Orders', data: [3,5,2,8,4,7,{{ $cust->order_count }}] }],
            chart: { height: 161, width: '100%', type: 'area', offsetY: 2, toolbar: { show: false }, sparkline: { enabled: true } },
            colors: ['{{ $colors[$i] ?? "#4F46E5" }}'],
            fill: { type: "gradient", gradient: { opacityFrom: 0.5, opacityTo: 0.5 } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: { type: 'datetime', categories: ["2025-01-01","2025-02-01","2025-03-01","2025-04-01","2025-05-01","2025-06-01","2025-07-01"], labels: { show: false } },
            yaxis: { show: false },
            grid: { show: false, padding: { top: 0, right: 0, bottom: 0, left: 0 } }
        }).render();
    }
    @endforeach

    // ── Tooltips ──────────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush