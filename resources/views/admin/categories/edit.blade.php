@extends('layouts.admin')
@section('title', 'Edit Category')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Edit Category</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible mb-20">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Edit: {{ $category->name }}</h4>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
            <div class="card-body pt-15">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-15">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-15">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control"
                               value="{{ old('slug', $category->slug) }}">
                        <small class="text-muted">Change carefully — affects product URLs</small>
                    </div>
                    <div class="mb-15">
                        <label class="form-label">Products in this category</label>
                        <div class="p-10 bg-light rounded">
                            <span class="badge bg-primary-transparent text-primary fs-14">
                                {{ $category->products->count() }} products
                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-10">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-save-line me-1"></i> Update Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
