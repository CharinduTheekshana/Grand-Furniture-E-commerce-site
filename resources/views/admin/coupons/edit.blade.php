@extends('layouts.admin')
@section('title', 'Edit Coupon')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Edit Coupon</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header justify-between">
                <h4 style="font-family:monospace;letter-spacing:2px;">{{ $coupon->code }}</h4>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
            <div class="card-body pt-15">

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible mb-20">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-15">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" name="code"
                               class="form-control text-uppercase @error('code') is-invalid @enderror"
                               value="{{ old('code', $coupon->code) }}"
                               style="text-transform:uppercase;letter-spacing:2px;">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Discount Type</label>
                        <select name="type" id="coupon-type" class="form-select" onchange="updatePrefix()">
                            <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed"   {{ old('type', $coupon->type) === 'fixed'   ? 'selected' : '' }}>Fixed Amount (LKR)</option>
                        </select>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Value</label>
                        <div class="input-group">
                            <span class="input-group-text" id="value-prefix">
                                {{ $coupon->type === 'percent' ? '%' : 'LKR' }}
                            </span>
                            <input type="number" name="value" step="0.01" min="0.01"
                                   class="form-control" value="{{ old('value', $coupon->value) }}">
                        </div>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Minimum Order (LKR)</label>
                        <div class="input-group">
                            <span class="input-group-text">LKR</span>
                            <input type="number" name="min_order" step="0.01" min="0"
                                   class="form-control" value="{{ old('min_order', $coupon->min_order) }}">
                        </div>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" min="1" class="form-control"
                               value="{{ old('usage_limit', $coupon->usage_limit) }}"
                               placeholder="Unlimited">
                        <small class="text-muted">Used {{ $coupon->used_count }} times so far</small>
                    </div>

                    <div class="mb-15">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                    </div>

                    <div class="mb-20">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="d-flex gap-10">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-save-line me-1"></i> Update Coupon
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updatePrefix() {
    var type = document.getElementById('coupon-type').value;
    document.getElementById('value-prefix').textContent = type === 'percent' ? '%' : 'LKR';
}
</script>
@endpush