@extends('layouts.admin')
@section('title', 'Customer - ' . $customer->name)

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Customer Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mt-1">
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-primary-transparent text-primary">
                    <i class="ri-shopping-bag-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Orders</span>
                    <h2 class="mb-5">{{ $totalOrders }}</h2>
                    <span class="fs-12 text-muted">All time</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-money-dollar-circle-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Spent</span>
                    <h2 class="mb-5">LKR {{ number_format($totalSpent, 0) }}</h2>
                    <span class="text-success fs-12">Lifetime value</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-time-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Pending Orders</span>
                    <h2 class="mb-5">{{ $pendingOrders }}</h2>
                    <span class="text-warning fs-12">Active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-info-transparent text-info">
                    <i class="ri-trophy-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Completed</span>
                    <h2 class="mb-5">{{ $completedOrders }}</h2>
                    <span class="fs-12 text-muted">Orders</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-20">

    {{-- Customer Info Card --}}
    <div class="col-xl-4 col-lg-4">
        <div class="card mb-20">
            <div class="card-body" style="text-align:center;padding:30px;">
                {{-- Avatar --}}
                <div style="width:80px;height:80px;border-radius:50%;
                            background:linear-gradient(135deg,#4F46E5,#7c3aed);
                            display:flex;align-items:center;justify-content:center;
                            font-size:32px;font-weight:700;color:#fff;
                            margin:0 auto 16px;">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <h4 style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $customer->name }}</h4>
                <p style="color:#999;font-size:13px;margin-bottom:20px;">{{ $customer->email }}</p>

                <div style="border-top:1px solid #f0f0f0;padding-top:16px;text-align:left;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-size:13px;color:#999;">Member Since</span>
                        <span style="font-size:13px;font-weight:600;">{{ $customer->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-size:13px;color:#999;">Last Order</span>
                        <span style="font-size:13px;font-weight:600;">
                            {{ $orders->first() ? $orders->first()->created_at->format('d M Y') : 'Never' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                        <span style="font-size:13px;color:#999;">Avg. Order Value</span>
                        <span style="font-size:13px;font-weight:600;">
                            LKR {{ $totalOrders > 0 ? number_format($totalSpent / $totalOrders, 0) : '0' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:13px;color:#999;">Email Verified</span>
                        <span style="font-size:13px;font-weight:600;">
                            @if($customer->email_verified_at)
                                <span style="color:#2ecc71;">✓ Verified</span>
                            @else
                                <span style="color:#e74c3c;">✗ Not Verified</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <a href="mailto:{{ $customer->email }}"
                       class="btn btn-primary btn-sm" style="width:100%;">
                        <i class="ri-mail-line me-1"></i> Send Email
                    </a>
                </div>
            </div>
        </div>

        {{-- Customer Tier --}}
        @php
            $tier = $totalSpent >= 100000 ? ['Gold', '#f59e0b', 'ri-vip-crown-line']
                  : ($totalSpent >= 50000 ? ['Silver', '#94a3b8', 'ri-medal-line']
                  : ['Regular', '#6b7280', 'ri-user-line']);
        @endphp
        <div class="card mb-20">
            <div class="card-body" style="text-align:center;padding:20px;">
                <i class="{{ $tier[2] }}" style="font-size:36px;color:{{ $tier[1] }};"></i>
                <h5 style="margin-top:8px;font-size:15px;font-weight:700;color:{{ $tier[1] }};">
                    {{ $tier[0] }} Customer
                </h5>
                <p style="font-size:12px;color:#999;margin:0;">
                    @if($tier[0] === 'Gold') Spending over LKR 100,000
                    @elseif($tier[0] === 'Silver') Spending over LKR 50,000
                    @else Valued customer @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Orders + Chart --}}
    <div class="col-xl-8 col-lg-8">

        {{-- Monthly Spending Chart --}}
        <div class="card mb-20">
            <div class="card-header justify-between">
                <h4><i class="ri-bar-chart-line me-1"></i> Monthly Spending (Last 6 Months)</h4>
            </div>
            <div class="card-body pt-15">
                <div id="customerSpendingChart"></div>
            </div>
        </div>

        {{-- Order History --}}
        <div class="card">
            <div class="card-header justify-between">
                <h4><i class="ri-shopping-bag-line me-1"></i> Order History</h4>
                <span class="badge bg-primary-transparent text-primary">{{ $totalOrders }} orders</span>
            </div>
            <div class="card-body pt-15">
                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php
                                $statusColors = [
                                    'pending'    => 'warning',
                                    'confirmed'  => 'info',
                                    'processing' => 'primary',
                                    'shipped'    => 'info',
                                    'delivered'  => 'success',
                                    'completed'  => 'success',
                                    'cancelled'  => 'danger',
                                    'paid'       => 'primary',
                                ];
                                $cls = $statusColors[$order->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td><strong>#GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->items->count() }} items</td>
                                <td><strong>LKR {{ number_format($order->total, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $cls }}-transparent text-{{ $cls }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="btn-icon btn-success-light" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="ri-shopping-bag-line fs-32 d-block mb-2"></i>
                    No orders yet.
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/plugins/apexcharts.min.js') }}"></script>
<script>
var options = {
    chart: { type: 'bar', height: 200, toolbar: { show: false } },
    series: [{
        name: 'Spent (LKR)',
        data: [@foreach($monthlyData as $m){{ $m['revenue'] }},@endforeach]
    }],
    xaxis: {
        categories: [@foreach($monthlyData as $m)'{{ $m['label'] }}',@endforeach]
    },
    colors: ['#4F46E5'],
    plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f0f0f0' },
    yaxis: {
        labels: {
            formatter: function(v) { return 'LKR ' + v.toLocaleString(); }
        }
    }
};
new ApexCharts(document.getElementById('customerSpendingChart'), options).render();
</script>
@endpush