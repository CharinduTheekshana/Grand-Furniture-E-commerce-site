{{-- ═══════════════════════════════════════════════════════════════
     resources/views/admin/products/edit.blade.php
═══════════════════════════════════════════════════════════════ --}}
@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
                <h1 class="page-title fs-18 lh-1">Edit Product</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-example1 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible mb-20">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-20">

            {{-- LEFT —— Main info --}}
            <div class="col-xxl-8 col-xl-8">

                <div class="card mb-20">
                    <div class="card-header justify-between">
                        <h4>Product Information</h4>
                        <a class="btn btn-light btn-sm" href="{{ route('admin.products.index') }}">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            <div class="col-xl-12">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xl-12">
                                <label class="form-label">Description</label>
                                <textarea name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="5">{{ old('description', $product->description) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card mb-20">
                    <div class="card-header"><h4>Pricing</h4></div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            <div class="col-xl-4">
                                <label class="form-label">Price (LKR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $product->price) }}">
                                </div>
                                @error('price') <div class="text-danger fs-12 mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xl-4">
                                <label class="form-label">Old Price (LKR)</label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" name="old_price" step="0.01" min="0"
                                           class="form-control"
                                           value="{{ old('old_price', $product->old_price) }}">
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <label class="form-label">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" name="discount" min="0" max="100"
                                           class="form-control"
                                           value="{{ old('discount', $product->discount ?? 0) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4>Product Image</h4></div>
                    <div class="card-body pt-15">

                        {{-- Current image preview --}}
                        @if($product->image)
                        <div class="mb-15">
                            <label class="form-label text-muted">Current Image</label><br>
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="height:100px;border-radius:8px;object-fit:cover;">
                        </div>
                        @endif

                        <label class="form-label">Replace Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>

            {{-- RIGHT —— Details --}}
            <div class="col-xxl-4 col-xl-4">

                <div class="card mb-20">
                    <div class="card-header"><h4>Product Details</h4></div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            <div class="col-xl-12">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock" min="0" class="form-control"
                                       value="{{ old('stock', $product->stock) }}">
                            </div>

                            {{-- Brand / Manufacturer --}}
                            <div class="col-xl-12">
                                <label class="form-label">Brand / Manufacturer</label>
                                <input type="text" name="brand" class="form-control"
                                    value="{{ old('brand', $product->brand) }}"
                                    placeholder="e.g. IKEA, Damro, Arpico">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Offer & Deal Section ─────────────────── --}}
<div class="card mb-20">
    <div class="card-header justify-between">
        <h4><i class="ri-price-tag-3-line me-1"></i> Offer & Deal</h4>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" name="offer_status"
                   id="offer-status-toggle" value="1" role="switch"
                   onchange="toggleOfferSection(this.checked)"
                   {{ old('offer_status', $product->offer_status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="offer-status-toggle">
                Enable Offer
            </label>
        </div>
    </div>
    <div class="card-body pt-15" id="offer-section">
        <div class="row g-3">

            {{-- Offer Badge Text --}}
            <div class="col-xl-6">
                <label class="form-label">Offer Badge Text</label>
                <input type="text" name="offer_badge" class="form-control"
                       value="{{ old('offer_badge', $product->offer_badge ?? '') }}"
                       placeholder="e.g. Flash Sale" list="badge-suggestions">
                <datalist id="badge-suggestions">
                    <option value="Up to 30% OFF">
                    <option value="Flash Sale">
                    <option value="Weekend Deal">
                    <option value="Buy 1 Get 1">
                    <option value="Free Delivery">
                    <option value="Clearance Sale">
                    <option value="Limited Offer">
                    <option value="Mega Deal">
                </datalist>
            </div>

            {{-- Offer Type --}}
            <div class="col-xl-6">
                <label class="form-label">Offer Type</label>
                <select name="offer_type" class="form-select">
                    <option value="">— Select Type —</option>
                    @php
                        $offerTypes = [
                            'percentage'    => 'Percentage Discount',
                            'fixed'         => 'Fixed Discount',
                            'free_delivery' => 'Free Delivery',
                            'bogo'          => 'Buy 1 Get 1',
                            'clearance'     => 'Clearance Sale',
                            'flash_sale'    => 'Flash Sale',
                            'weekend'       => 'Weekend Deal',
                            'mega'          => 'Mega Deal',
                            'custom'        => 'Custom',
                        ];
                    @endphp
                    @foreach($offerTypes as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('offer_type', $product->offer_type ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-xl-6">
                <label class="form-label">Offer Start Date</label>
                <input type="datetime-local" name="offer_start_date" class="form-control"
                       value="{{ old('offer_start_date', $product->offer_start_date?->format('Y-m-d\TH:i') ?? '') }}">
                <small class="text-muted">Leave blank = starts immediately</small>
            </div>

            {{-- End Date --}}
            <div class="col-xl-6">
                <label class="form-label">Offer End Date</label>
                <input type="datetime-local" name="offer_end_date" class="form-control"
                       value="{{ old('offer_end_date', $product->offer_end_date?->format('Y-m-d\TH:i') ?? '') }}">
                <small class="text-muted">Leave blank = no expiry</small>
            </div>

            {{-- Preview --}}
            <div class="col-xl-12">
                <label class="form-label">Badge Preview</label>
                <div>
                    <span id="badge-preview"
                          style="display:inline-block;padding:4px 12px;border-radius:20px;
                                 font-size:11px;font-weight:700;color:#fff;
                                 background:#2c3e50;letter-spacing:0.4px;">
                        {{ old('offer_badge', $product->offer_badge ?? 'Preview') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

                {{-- Colors Card with Toggle --}}
                <div class="card mb-20">
                    <div class="card-header justify-between">
                        <h4>Product Colors</h4>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                id="enable-colors-toggle" role="switch"
                                onchange="toggleColorsSection(this.checked)"
                                {{ isset($selectedColorIds) && count($selectedColorIds) > 0 ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="enable-colors-toggle">
                                Enable Colors
                            </label>
                        </div>
                    </div>
                    <div class="card-body pt-15" id="colors-section"
                        style="{{ isset($selectedColorIds) && count($selectedColorIds) > 0 ? '' : 'display:none;' }}">
                        @if(isset($allColors) && $allColors->isNotEmpty())
                            <p class="text-muted fs-13 mb-15">
                                Select colors available for this product:
                            </p>
                            <div class="d-flex flex-wrap gap-10">
                                @foreach($allColors as $color)
                                <div class="form-check" style="min-width:120px;">
                                    <input class="form-check-input" type="checkbox"
                                        name="color_ids[]"
                                        value="{{ $color->id }}"
                                        id="color-{{ $color->id }}"
                                        {{ isset($selectedColorIds) && in_array($color->id, $selectedColorIds) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center gap-8"
                                        for="color-{{ $color->id }}"
                                        style="cursor:pointer;">
                                        @if($color->color_code)
                                        <span style="display:inline-block;width:16px;height:16px;
                                                    border-radius:50%;
                                                    background:{{ $color->color_code }};
                                                    border:1px solid #ddd;
                                                    flex-shrink:0;"></span>
                                        @endif
                                        {{ $color->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                No colors defined yet.
                                <a href="{{ route('admin.colors.index') }}" target="_blank">
                                    Add colors first <i class="ri-external-link-line"></i>
                                </a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="card mb-20">
                    <div class="card-header"><h4>Visibility</h4></div>
                    <div class="card-body pt-15">

                        <div class="form-check form-switch mb-15">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                                <small class="d-block text-muted">Show on home page</small>
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active / Published
                            </label>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-save-line me-1"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light w-100 mt-10">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script>
function toggleColorsSection(show) {
    var section = document.getElementById('colors-section');
    section.style.display = show ? 'block' : 'none';
    if (!show) {
        document.querySelectorAll('input[name="color_ids[]"]')
            .forEach(function(cb) { cb.checked = false; });
    }
}
// Offer section toggle
function toggleOfferSection(show) {
    document.getElementById('offer-section').style.display = show ? 'block' : 'none';
}

var badgeColors = {
    'flash_sale':    'linear-gradient(135deg,#e74c3c,#c0392b)',
    'free_delivery': 'linear-gradient(135deg,#27ae60,#1e8449)',
    'bogo':          'linear-gradient(135deg,#2980b9,#1a5276)',
    'clearance':     'linear-gradient(135deg,#e67e22,#d35400)',
    'weekend':       'linear-gradient(135deg,#8e44ad,#6c3483)',
    'mega':          'linear-gradient(135deg,#922b21,#7b241c)',
    'percentage':    'linear-gradient(135deg,#e74c3c,#c0392b)',
    'fixed':         'linear-gradient(135deg,#16a085,#1abc9c)',
    'default':       'linear-gradient(135deg,#2c3e50,#34495e)',
};

var badgeInput   = document.querySelector('input[name="offer_badge"]');
var typeSelect   = document.querySelector('select[name="offer_type"]');
var badgePreview = document.getElementById('badge-preview');

if (badgeInput) {
    badgeInput.addEventListener('input', function() {
        if (badgePreview) badgePreview.textContent = this.value || 'Preview';
    });
}
if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        var color = badgeColors[this.value] || badgeColors['default'];
        if (badgePreview) badgePreview.style.background = color;
    });
}

</script>



@endpush