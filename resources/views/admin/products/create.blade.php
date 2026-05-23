@extends('layouts.admin')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Add New Product</h3></div></div></div></div></div>
<div class="login-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="login-title"><h3>Product Information</h3></div>
                <div class="login-form">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group login-page">
                            <label>Product Name <span>*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="text-danger" style="font-size:12px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group login-page">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group login-page">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group login-page">
                            <label>Price (LKR) <span>*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                            @error('price')<span class="text-danger" style="font-size:12px">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group login-page">
                            <label>Old Price (LKR) <span style="color:#999;font-size:12px">(Optional — for sale badge)</span></label>
                            <input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price') }}">
                        </div>
                        <div class="form-group login-page">
                            <label>Stock <span>*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
                        </div>
                        <div class="form-group login-page">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}> Featured Product</label>
                        </div>
                        <div style="display:flex;gap:10px;margin-top:20px;">
                            <button type="submit" class="btn btn-default login-btn"><i class="fa fa-plus"></i> Add Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-default login-btn" style="background:#999;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection