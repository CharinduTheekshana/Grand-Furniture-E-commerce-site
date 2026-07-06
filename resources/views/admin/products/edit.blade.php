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

                        {{-- Main image: current preview + replace input, side by side --}}
                        <label class="form-label">Main Image</label>
                        <div class="d-flex gap-15 align-items-start flex-wrap mb-5">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:90px;height:90px;border-radius:8px;object-fit:cover;
                                        border:1px solid #e5e7eb;flex-shrink:0;">
                            @endif
                            <div style="flex:1;min-width:180px;">
                                <input type="file" name="image" accept="image/*"
                                       class="form-control @error('image') is-invalid @enderror">
                                <small class="text-muted">Leave empty to keep current image</small>
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-15">

                        {{-- Gallery: existing thumbnails + an "add" tile, in one row --}}
                        <div class="d-flex justify-content-between align-items-center mb-10">
                            <label class="form-label mb-0">Gallery Images</label>
                            <small class="text-muted">{{ $product->images->count() }} image(s)</small>
                        </div>

                        <div class="d-flex flex-wrap gap-10">
                            @foreach($product->images as $img)
                            <div class="gallery-thumb" data-image-id="{{ $img->id }}" style="width:80px;">
                                <div style="position:relative;width:80px;height:80px;">
                                    <img src="{{ $img->image_url }}"
                                         style="width:80px;height:80px;object-fit:cover;border-radius:6px;
                                                border:1px solid #e5e7eb;">
                                    <button type="button"
                                            class="btn-delete-gallery-image"
                                            data-url="{{ route('admin.products.images.destroy', $img) }}"
                                            style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;
                                                   border-radius:50%;background:#dc2626;color:#fff;border:2px solid #fff;
                                                   font-size:11px;line-height:1;cursor:pointer;padding:0;">
                                        &times;
                                    </button>
                                </div>
                                @if($product->colors->count())
                                <select class="form-select form-select-sm mt-5 select-image-color"
                                        data-url="{{ route('admin.products.images.color', $img) }}"
                                        style="font-size:10px;padding:2px 4px;height:auto;">
                                    <option value="">No color</option>
                                    @foreach($product->colors as $c)
                                    <option value="{{ $c->id }}" {{ $img->color_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            @endforeach

                            {{-- Add-images tile: styled dropzone that wraps a hidden multi-file input --}}
                            <label for="gallery-upload-input"
                                   style="width:80px;height:80px;border:1.5px dashed #c7cdd6;border-radius:6px;
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
                        @if($product->colors->count())
                        <small class="text-muted d-block mt-10">
                            Tip: assign each photo to a color so customers see the matching photo when they pick that color.
                        </small>
                        @endif
                        <div id="gallery-preview-row" class="d-flex flex-wrap gap-10 mt-10"></div>
                        <small id="gallery-file-count" class="text-muted d-block mt-10"></small>
                        <small class="text-muted d-block">JPG, PNG, WEBP — max 4MB each</small>
                        @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
    countEl.textContent = files.length + ' new file(s) selected — will be added when you click Update Product';

    files.forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var div = document.createElement('div');
            div.style.cssText = 'width:72px;height:72px;border-radius:6px;overflow:hidden;border:2px solid #6366f1;position:relative;flex-shrink:0;';
            div.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">' +
                             '<span style="position:absolute;bottom:0;left:0;right:0;background:rgba(99,102,241,.9);color:#fff;font-size:9px;text-align:center;">NEW</span>';
            row.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// Assign a color to a gallery image via AJAX
$(document).on('change', '.select-image-color', function() {
    var $select = $(this);
    var url = $select.data('url');
    $.ajax({
        url: url,
        method: 'PATCH',
        data: { color_id: $select.val() },
        success: function() {
            $select.css('border-color', '#16a34a');
            setTimeout(function() { $select.css('border-color', ''); }, 800);
        },
        error: function() {
            alert('Could not update the color. Please try again.');
        }
    });
});

// Delete a gallery image via AJAX (avoids nesting a <form> inside the main product form)
$(document).on('click', '.btn-delete-gallery-image', function() {
    if (!confirm('Remove this image?')) return;

    var $btn  = $(this);
    var $tile = $btn.closest('.gallery-thumb');
    var url   = $btn.data('url');

    $.ajax({
        url: url,
        method: 'DELETE',
        success: function() {
            $tile.fadeOut(150, function() { $(this).remove(); });
        },
        error: function() {
            alert('Could not remove the image. Please try again.');
        }
    });
});

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