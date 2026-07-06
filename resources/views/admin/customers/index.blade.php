@extends('layouts.admin')
@section('title', 'Customers')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Customers List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Customers</li>
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
                    <i class="ri-user-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Customers</span>
                    <h2 class="mb-5">{{ $totalCustomers }}</h2>
                    <span class="fs-12 text-muted">Registered</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-user-add-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">New This Month</span>
                    <h2 class="mb-5">{{ $newCustomers }}</h2>
                    <span class="text-success fs-12">This month</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-star-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">With Orders</span>
                    <h2 class="mb-5">{{ $withOrders }}</h2>
                    <span class="fs-12 text-muted">Active buyers</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-info-transparent text-info">
                    <i class="ri-wallet-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Revenue</span>
                    <h2 class="mb-5" style="font-size:18px;">LKR {{ number_format($totalRevenue) }}</h2>
                    <span class="fs-12 text-muted">From customers</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Customers Table --}}
<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Customers List</h4>
            </div>
            <div class="card-body pt-15">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spent (LKR)</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>
                                    <div class="d-flex-items gap-10">
                                        <div class="avatar avatar-md radius-100 bg-primary-transparent text-primary fw-7"
                                             style="width:38px;height:38px;display:flex;align-items:center;
                                                    justify-content:center;border-radius:50%;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="badge bg-primary-transparent text-primary">
                                        {{ $customer->orders_count ?? 0 }} orders
                                    </span>
                                </td>
                                <td class="fw-medium">
                                    {{ number_format($customer->total_spent ?? 0, 2) }}
                                </td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($customer->orders_count > 0)
                                        <span class="badge bg-success-transparent text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary-transparent text-secondary">No Orders</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}"
                                           class="btn-icon btn-success-light" title="View Profile">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="mailto:{{ $customer->email }}"
                                           class="btn-icon btn-info-light" title="Send Email">
                                            <i class="ri-mail-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-group-line fs-32 d-block mb-10"></i>
                                    No customers yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('layouts.admin')
@section('title', 'Orders')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Order List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Ecommerce</a></li>
                    <li class="breadcrumb-item active">Order List</li>
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
                    <i class="ri-checkbox-circle-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Completed</span>
                    <h2 class="mb-5">{{ $completedOrders }}</h2>
                    <span class="text-success fs-12">Delivered</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-truck-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Processing</span>
                    <h2 class="mb-5">{{ $processingOrders }}</h2>
                    <span class="fs-12 text-muted">In progress</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-danger-transparent text-danger">
                    <i class="ri-close-circle-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Cancelled</span>
                    <h2 class="mb-5">{{ $cancelledOrders }}</h2>
                    <span class="text-danger fs-12">Cancelled</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Orders Table --}}
<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Order List</h4>
                <div class="d-flex flex-wrap gap-15">
                    {{-- Status filter --}}
                    <select class="form-select" style="width:160px;" onchange="filterStatus(this.value)">
                        <option value="">All Status</option>
                        @foreach(['pending','confirmed','processing','shipped','delivered','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body pt-15">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Items</th>
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
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-medium text-primary">
                                        #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex-items gap-10">
                                        <div class="avatar avatar-sm radius-100 bg-primary-transparent text-primary fw-7"
                                             style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                                            {{ strtoupper(substr($order->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-13">{{ $order->name }}</h6>
                                            <small class="text-muted">{{ $order->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $order->phone ?? '—' }}</td>
                                <td>{{ $order->items->count() }}</td>
                                <td class="fw-medium">{{ number_format($order->total, 2) }}</td>
                                <td><span class="badge {{ $cls }} order-status-badge">{{ ucfirst($order->status) }}</span></td>
                                <td>
                                    {{ $order->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <a class="btn-icon btn-success-light"
                                       href="{{ route('admin.orders.show', $order) }}" title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-shopping-bag-3-line fs-32 d-block mb-10"></i>
                                    No orders yet.
                                </td>
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
<script src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dataTableDefault').DataTable({
            pageLength: 15,
            order: [[0, 'desc']],
            columnDefs: [{ orderable: false, targets: [7] }]
        });
    }
});
function filterStatus(val) {
    window.location.href = '{{ url("/admin-panel/orders") }}' + (val ? '?status=' + val : '');
}
</script>

<script>
// ── Realtime order status update ──────────────────
if (typeof Echo !== 'undefined') {
    Echo.channel('admin-dashboard')
        .listen('.order.status.updated', function(data) {
            var badge = document.querySelector('[data-order-id="' + data.id + '"] .order-status-badge');
            if (badge) {
                badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                badge.className = 'badge order-status-badge status-' + data.status;
            }
            if (typeof showToast === 'function') {
                showToast('Order #' + data.id + ' status updated to ' + data.status, 'info');
            }
        });
}
</script>

@endpush