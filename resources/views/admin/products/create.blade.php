{{-- ═══════════════════════════════════════════════════════════════
     resources/views/admin/products/create.blade.php
     Dashnix ecommerce-add-product.html → Laravel Blade
═══════════════════════════════════════════════════════════════ --}}
@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

    {{-- Page title --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
                <h1 class="page-title fs-18 lh-1">Add Product</h1>
                
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-example1 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible mb-20">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-20">

            {{-- ── LEFT COLUMN — Main product info ─────────── --}}
            <div class="col-xxl-8 col-xl-8">

                {{-- Basic info card --}}
                <div class="card mb-20">
                    <div class="card-header justify-between">
                        <h4>Product Information</h4>
                        <a class="btn btn-light btn-sm" href="{{ route('admin.products.index') }}">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            {{-- Name --}}
                            <div class="col-xl-12">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="e.g. Luxury Sofa Set">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-xl-12">
                                <label class="form-label">Product Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="5" placeholder="Describe the product...">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Pricing card --}}
                <div class="card mb-20">
                    <div class="card-header">
                        <h4>Pricing</h4>
                    </div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            {{-- Price --}}
                            <div class="col-xl-4">
                                <label class="form-label">Price (LKR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price') }}" placeholder="0.00">
                                </div>
                                @error('price') <div class="text-danger fs-12 mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Old price --}}
                            <div class="col-xl-4">
                                <label class="form-label">Old Price (LKR)</label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" name="old_price" step="0.01" min="0"
                                           class="form-control @error('old_price') is-invalid @enderror"
                                           value="{{ old('old_price') }}" placeholder="0.00">
                                </div>
                            </div>

                            {{-- Discount --}}
                            <div class="col-xl-4">
                                <label class="form-label">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" name="discount" min="0" max="100"
                                           class="form-control @error('discount') is-invalid @enderror"
                                           value="{{ old('discount', 0) }}" placeholder="0">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Image upload card --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Product Image</h4>
                    </div>
                    <div class="card-body pt-15">
                        <label class="form-label">Main Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <hr class="my-15">

                        <label class="form-label">Gallery Images <span class="text-muted fs-12">(optional)</span></label>
                        <div class="d-flex flex-wrap gap-10">
                            <label for="gallery-upload-input"
                                   style="width:72px;height:72px;border:1.5px dashed #c7cdd6;border-radius:6px;
                                          display:flex;flex-direction:column;align-items:center;justify-content:center;
                                          cursor:pointer;color:#8a93a3;transition:border-color .15s;flex-shrink:0;"
                                   onmouseover="this.style.borderColor='#6366f1'"
                                   onmouseout="this.style.borderColor='#c7cdd6'">
                                <i class="ri-add-line fs-18"></i>
                                <span style="font-size:10px;">Add</span>
                            </label>
                            <input id="gallery-upload-input" type="file" name="images[]" accept="image/*" multiple
                                   class="d-none @error('images.*') is-invalid @enderror"
                                   onchange="previewGalleryFiles(this)">
                        </div>
                        <div id="gallery-preview-row" class="d-flex flex-wrap gap-10 mt-10"></div>
                        <small id="gallery-file-count" class="text-muted d-block mt-10"></small>
                        <small class="text-muted d-block">Select multiple — JPG, PNG, WEBP, max 4MB each</small>
                        @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>
            {{-- left column end --}}

            {{-- ── RIGHT COLUMN — Category, stock, flags ───── --}}
            <div class="col-xxl-4 col-xl-4">

                {{-- Category & stock --}}
                <div class="card mb-20">
                    <div class="card-header">
                        <h4>Product Details</h4>
                    </div>
                    <div class="card-body pt-15">
                        <div class="row gy-15">

                            {{-- Category --}}
                            <div class="col-xl-12">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Stock --}}
                            <div class="col-xl-12">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', 0) }}" placeholder="0">
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Brand / Manufacturer --}}
                            <div class="col-xl-12">
                                <label class="form-label">Brand / Manufacturer</label>
                                <input type="text" name="brand" class="form-control"
                                    value="{{ old('brand') }}"
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
                                <small class="text-muted">Text shown on product badge</small>
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
                                    value="{{ old('offer_start_date', isset($product->offer_start_date) ? $product->offer_start_date?->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Leave blank = starts immediately</small>
                            </div>

                            {{-- End Date --}}
                            <div class="col-xl-6">
                                <label class="form-label">Offer End Date</label>
                                <input type="datetime-local" name="offer_end_date" class="form-control"
                                    value="{{ old('offer_end_date', isset($product->offer_end_date) ? $product->offer_end_date?->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Leave blank = no expiry</small>
                            </div>

                            {{-- Preview --}}
                            <div class="col-xl-12">
                                <label class="form-label">Badge Preview</label>
                                <div id="badge-preview-area">
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
                                onchange="toggleColorsSection(this.checked)">
                            <label class="form-check-label fw-medium" for="enable-colors-toggle">
                                Enable Colors
                            </label>
                        </div>
                    </div>
                    <div class="card-body pt-15" id="colors-section" style="display:none;">
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
                                        id="color-{{ $color->id }}">
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

                {{-- Flags card --}}
                <div class="card mb-20">
                    <div class="card-header">
                        <h4>Visibility</h4>
                    </div>
                    <div class="card-body pt-15">

                        {{-- Featured --}}
                        <div class="form-check form-switch mb-15">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                                <small class="d-block text-muted">Show on home page</small>
                            </label>
                        </div>

                        {{-- Active --}}
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active / Published
                                <small class="d-block text-muted">Visible in the shop</small>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-save-line me-1"></i> Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light w-100 mt-10">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
            {{-- right column end --}}

        </div>
    </form>

@endsection

@push('scripts')
<script>
// Show live thumbnail previews for newly selected gallery files (not yet uploaded)
function previewGalleryFiles(input) {
    var row = document.getElementById('gallery-preview-row');
    var countEl = document.getElementById('gallery-file-count');
    row.innerHTML = '';

    var files = Array.from(input.files || []);
    if (!files.length) {
        countEl.textContent = '';
        return;
    }
    countEl.textContent = files.length + ' file(s) selected — will be added when you click Save Product';

    files.forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var div = document.createElement('div');
            div.style.cssText = 'width:72px;height:72px;border-radius:6px;overflow:hidden;border:2px solid #6366f1;position:relative;flex-shrink:0;';
            div.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
            row.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

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

// Badge preview
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

document.querySelector('input[name="offer_badge"]').addEventListener('input', function() {
    document.getElementById('badge-preview').textContent = this.value || 'Preview';
});

document.querySelector('select[name="offer_type"]').addEventListener('change', function() {
    var color = badgeColors[this.value] || badgeColors['default'];
    document.getElementById('badge-preview').style.background = color;
});

</script>
@endpush