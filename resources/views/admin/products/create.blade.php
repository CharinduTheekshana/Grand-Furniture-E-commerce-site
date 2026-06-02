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

                        </div>
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
