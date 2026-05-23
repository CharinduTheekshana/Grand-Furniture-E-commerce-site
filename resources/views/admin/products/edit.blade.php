@extends('layouts.admin')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Edit Product</h3></div></div></div></div></div>
<div class="login-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="login-title"><h3>Edit: {{ $product->name }}</h3></div>
                <div class="login-form">
                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="form-group login-page">
                            <label>Product Name <span>*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="form-group login-page">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group login-page">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                        </div>
                        <div class="form-group login-page">
                            <label>Price (LKR) <span>*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="form-group login-page">
                            <label>Old Price (LKR)</label>
                            <input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price', $product->old_price) }}">
                        </div>
                        <div class="form-group login-page">
                            <label>Stock <span>*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                        </div>
                        <div class="form-group login-page">
                            <label>Replace Image <span style="color:#999;font-size:12px">(Leave empty to keep current)</span></label>
                            @if($product->image)
                            <div style="margin-bottom:8px;">
                                <img src="{{ $product->image_url }}" style="height:80px;border:1px solid #eee;padding:3px;" alt="Current">
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> Featured Product</label>
                        </div>
                        <div style="display:flex;gap:10px;margin-top:20px;">
                            <button type="submit" class="btn btn-default login-btn"><i class="fa fa-save"></i> Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-default login-btn" style="background:#999;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                @if($product->image)
                <div class="login-title"><h3>Current Image</h3></div>
                <img src="{{ $product->image_url }}" class="img-fluid" style="border:1px solid #eee;padding:5px;">
                @endif
                <div style="margin-top:20px;">
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-default login-btn w-100" style="background:#e74c3c;"><i class="fa fa-trash"></i> Delete Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection