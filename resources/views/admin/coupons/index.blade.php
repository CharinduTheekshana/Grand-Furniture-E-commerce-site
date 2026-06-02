@extends('layouts.admin')
@section('title', 'Coupon Codes')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Coupon Codes</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-primary-transparent text-primary">
                    <i class="ri-coupon-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Coupons</span>
                    <h2 class="mb-5">{{ $totalCoupons }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-check-double-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Active</span>
                    <h2 class="mb-5">{{ $activeCoupons }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-refresh-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Used</span>
                    <h2 class="mb-5">{{ $totalUsed }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-danger-transparent text-danger">
                    <i class="ri-close-circle-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Expired</span>
                    <h2 class="mb-5">{{ $expiredCoupons }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-20">

    {{-- Coupons Table --}}
    <div class="col-xxl-8">
        <div class="card">
            <div class="card-header justify-between">
                <h4><i class="ri-coupon-line me-1"></i> All Coupons</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Min Order</th>
                                <th>Used</th>
                                <th>Expires</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            @php
                                $status = $coupon->status_badge;
                                $statusClass = match($status) {
                                    'Active'   => 'bg-success-transparent text-success',
                                    'Inactive' => 'bg-secondary-transparent text-secondary',
                                    'Expired'  => 'bg-danger-transparent text-danger',
                                    'Used up'  => 'bg-warning-transparent text-warning',
                                    default    => 'bg-secondary-transparent text-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-medium"
                                          style="font-family:monospace;font-size:14px;letter-spacing:1px;">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $coupon->type === 'percent' ? 'bg-info-transparent text-info' : 'bg-primary-transparent text-primary' }}">
                                        {{ ucfirst($coupon->type) }}
                                    </span>
                                </td>
                                <td class="fw-medium">
                                    {{ $coupon->type === 'percent' ? $coupon->value . '%' : 'LKR ' . number_format($coupon->value, 2) }}
                                </td>
                                <td>{{ $coupon->min_order > 0 ? 'LKR ' . number_format($coupon->min_order, 2) : '—' }}</td>
                                <td>
                                    {{ $coupon->used_count }}
                                    @if($coupon->usage_limit) / {{ $coupon->usage_limit }} @endif
                                </td>
                                <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : '—' }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                                <td>
                                    <div class="d-flex gap-8">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                           class="btn-icon btn-info-light" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-coupon-line fs-32 d-block mb-10"></i>
                                    No coupons yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Form --}}
    <div class="col-xxl-4">
        <div class="card">
            <div class="card-header"><h4>Create Coupon</h4></div>
            <div class="card-body pt-15">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible mb-15">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        @foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach
                    </div>
                    @endif

                    <div class="mb-15">
                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="code" id="coupon-code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}" placeholder="SAVE20"
                                   style="text-transform:uppercase;letter-spacing:2px;">
                            <button type="button" class="btn btn-light" onclick="generateCode()">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                        @error('code')<div class="text-danger fs-12 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="type" id="coupon-type" class="form-select" onchange="updatePrefix()">
                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed"   {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (LKR)</option>
                        </select>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="value-prefix">%</span>
                            <input type="number" name="value" step="0.01" min="0.01"
                                   class="form-control @error('value') is-invalid @enderror"
                                   value="{{ old('value') }}" placeholder="20">
                        </div>
                        @error('value')<div class="text-danger fs-12 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Minimum Order (LKR)</label>
                        <div class="input-group">
                            <span class="input-group-text">LKR</span>
                            <input type="number" name="min_order" step="0.01" min="0"
                                   class="form-control" value="{{ old('min_order', 0) }}">
                        </div>
                        <small class="text-muted">0 = no minimum</small>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" min="1" class="form-control"
                               value="{{ old('usage_limit') }}" placeholder="Unlimited">
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at') }}" min="{{ date('Y-m-d') }}">
                        <small class="text-muted">Leave blank = no expiry</small>
                    </div>

                    <div class="mb-20">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active immediately</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-coupon-line me-1"></i> Create Coupon
                    </button>
                </form>
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
        $('#dataTableDefault').DataTable({ pageLength: 15, order: [[0, 'asc']] });
    }
    $('input[name="code"]').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
});
function generateCode() {
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var code = '';
    for (var i = 0; i < 8; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('coupon-code').value = code;
}
function updatePrefix() {
    var type = document.getElementById('coupon-type').value;
    document.getElementById('value-prefix').textContent = type === 'percent' ? '%' : 'LKR';
}
</script>
@endpush