@extends('layouts.admin')
@section('title', 'Categories')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Categories</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Categories</li>
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
                    <i class="ri-list-check-2 fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Categories</span>
                    <h2 class="mb-5">{{ $totalCategories }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-box-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Products</span>
                    <h2 class="mb-5">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-20">
    <div class="col-xxl-8 col-xl-8">
        <div class="card">
            <div class="card-header justify-between">
                <h4><i class="ri-list-check-2 me-1"></i> All Categories</h4>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add Category
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td class="fw-medium">{{ $category->name }}</td>
                                <td><span class="text-muted">{{ $category->slug }}</span></td>
                                <td>
                                    <span class="badge bg-primary-transparent text-primary">
                                        {{ $category->products_count }} products
                                    </span>
                                </td>
                                <td>{{ $category->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-8">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn-icon btn-info-light" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete \'{{ addslashes($category->name) }}\'? Products will lose this category.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger-light" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-list-check-2 fs-32 d-block mb-10"></i>
                                    No categories yet.
                                    <a href="{{ route('admin.categories.create') }}">Add first category</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Add Form --}}
    <div class="col-xxl-4 col-xl-4">
        <div class="card">
            <div class="card-header"><h4>Quick Add Category</h4></div>
            <div class="card-body pt-15">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible mb-15">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        @foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach
                    </div>
                    @endif
                    <div class="mb-15">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Living Room">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-15">
                        <label class="form-label">Slug <span class="text-muted fs-12">(auto-generated)</span></label>
                        <input type="text" name="slug" id="slug-preview" class="form-control"
                               value="{{ old('slug') }}" placeholder="living-room">
                        <small class="text-muted">Leave blank to auto-generate</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-add-line me-1"></i> Add Category
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
    // Auto-generate slug from name
    $('input[name="name"]').on('input', function() {
        var slug = $(this).val().toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug-preview').val(slug);
    });
});
</script>
@endpush
