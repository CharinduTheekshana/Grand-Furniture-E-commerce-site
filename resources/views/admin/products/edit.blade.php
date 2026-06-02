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

                        </div>
                    </div>
                </div>

                <div class="card mb-20">
                    <div class="card-header"><h4>Visibility</h4></div>
                    <div class="card-body pt-15">

                        <div class="form-check form-switch mb-15">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                                <small class="d-block text-muted">Show on home page</small>
                            </label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $product->is_active ?? 1) ? 'checked' : '' }}>
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
