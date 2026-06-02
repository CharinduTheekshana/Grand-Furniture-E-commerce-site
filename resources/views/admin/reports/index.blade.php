@extends('layouts.admin')
@section('title', 'Sales Reports')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Sales Reports</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Date Filter --}}
<div class="row mt-1 mb-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-15">
                <form method="GET" action="{{ route('admin.reports.index') }}"
                      class="d-flex flex-wrap align-items-end gap-15">
                    <div>
                        <label class="form-label mb-5">From</label>
                        <input type="date" name="from" class="form-control"
                               value="{{ $from->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="form-label mb-5">To</label>
                        <input type="date" name="to" class="form-control"
                               value="{{ $to->format('Y-m-d') }}">
                    </div>
                    <div class="d-flex gap-10">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-filter-line me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-light">Reset</a>
                        <a href="{{ route('admin.reports.export', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
                           class="btn btn-success text-white">
                            <i class="ri-download-line me-1"></i> Export CSV
                        </a>
                    </div>
                    <span class="text-muted fs-13">
                        Showing: {{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-20">
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-wallet-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Revenue</span>
                    <h2 class="mb-5" style="font-size:20px;">LKR {{ number_format($totalRevenue) }}</h2>
                    <span class="fs-12 text-muted">Paid orders only</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-primary-transparent text-primary">
                    <i class="ri-shopping-bag-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Orders</span>
                    <h2 class="mb-5">{{ $totalOrders }}</h2>
                    <span class="fs-12 text-muted">All statuses</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-bar-chart-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Avg. Order Value</span>
                    <h2 class="mb-5" style="font-size:20px;">LKR {{ number_format($avgOrderValue) }}</h2>
                    <span class="fs-12 text-muted">Per order</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-info-transparent text-info">
                    <i class="ri-user-add-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">New Customers</span>
                    <h2 class="mb-5">{{ $newCustomers }}</h2>
                    <span class="fs-12 text-muted">In this period</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Revenue Chart + Status Breakdown --}}
<div class="row g-3 mb-20">
    <div class="col-xxl-8">
        <div class="card">
            <div class="card-header"><h4>Revenue Over Time</h4></div>
            <div class="card-body pt-15">
                <div id="revenue-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4">
        <div class="card">
            <div class="card-header"><h4>Orders by Status</h4></div>
            <div class="card-body pt-15">
                @php
                    $statusColors = [
                        'pending'    => 'bg-warning-transparent text-warning',
                        'confirmed'  => 'bg-info-transparent text-info',
                        'processing' => 'bg-primary-transparent text-primary',
                        'shipped'    => 'bg-info-transparent text-info',
                        'delivered'  => 'bg-success-transparent text-success',
                        'completed'  => 'bg-success-transparent text-success',
                        'cancelled'  => 'bg-danger-transparent text-danger',
                    ];
                @endphp
                @forelse($byStatus as $s)
                <div class="d-flex-between mb-15">
                    <div class="d-flex-items gap-10">
                        <span class="badge {{ $statusColors[$s->status] ?? 'bg-secondary-transparent text-secondary' }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </div>
                    <div class="text-end">
                        <div class="fw-medium">{{ $s->count }} orders</div>
                        <small class="text-muted">LKR {{ number_format($s->revenue) }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">No orders in this period</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Top Products --}}
<div class="row g-3 mb-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header"><h4>Top Selling Products</h4></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue (LKR)</th>
                                <th>Avg Price (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             style="width:38px;height:38px;object-fit:cover;border-radius:6px;">
                                        @endif
                                        <span class="fw-medium">{{ $item->product->name ?? 'Deleted Product' }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->total_sold }}</td>
                                <td class="fw-medium text-success">{{ number_format($item->revenue, 2) }}</td>
                                <td>{{ $item->total_sold > 0 ? number_format($item->revenue / $item->total_sold, 2) : '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No sales data in this period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Orders Table --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Orders in Period</h4>
                <span class="badge bg-primary-transparent text-primary">{{ $orders->total() }} orders</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total (LKR)</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
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
                            <tr>
                                <td class="fw-medium text-primary">
                                    #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <div>{{ $order->name }}</div>
                                    <small class="text-muted">{{ $order->email }}</small>
                                </td>
                                <td class="fw-medium">{{ number_format($order->total, 2) }}</td>
                                <td><span class="badge {{ $cls }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn-icon btn-success-light" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders in this period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($orders, 'links'))
                <div class="p-3">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var options = {
        chart: { height: 320, type: 'line', toolbar: { show: false } },
        series: [
            { name: 'Revenue (LKR)', type: 'area', data: {!! json_encode($chartRevenue) !!}, color: '#4F46E5' },
            { name: 'Orders', type: 'column', data: {!! json_encode($chartOrders) !!}, color: '#FEBB7B' }
        ],
        stroke: { width: [2, 0], curve: 'smooth' },
        fill: { opacity: [0.2, 1] },
        labels: {!! json_encode($chartLabels) !!},
        xaxis: { type: 'category', labels: { style: { colors: 'var(--color-body)', fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: 'var(--color-body)' } } },
        legend: { position: 'top', labels: { colors: 'var(--color-body)' } },
        tooltip: { shared: true, intersect: false },
        plotOptions: { bar: { columnWidth: '40%' } }
    };
    if (document.querySelector('#revenue-chart')) {
        new ApexCharts(document.querySelector('#revenue-chart'), options).render();
    }
});
</script>
@endpush