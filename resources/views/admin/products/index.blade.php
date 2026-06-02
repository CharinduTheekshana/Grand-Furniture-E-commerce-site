@extends('layouts.admin')
@section('title', 'Products')

@section('content')

{{-- Page Title --}}
<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Product List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Ecommerce</a></li>
                    <li class="breadcrumb-item active">Product List</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mt-1">
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-primary-transparent text-primary">
                    <i class="ri-box-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Products</span>
                    <h2 class="mb-5">{{ $totalProducts }}</h2>
                    <span class="fs-12 text-muted">All products</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-store-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">In Stock</span>
                    <h2 class="mb-5">{{ $inStock }}</h2>
                    <span class="text-success fs-12">Available</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-shopping-cart-2-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Sold</span>
                    <h2 class="mb-5">{{ $totalSold }}</h2>
                    <span class="fs-12 text-muted">All time</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-danger-transparent text-danger">
                    <i class="ri-close-circle-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Out of Stock</span>
                    <h2 class="mb-5">{{ $outOfStock }}</h2>
                    <span class="text-danger fs-12">Needs restock</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Products Table --}}
<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4 class="d-flex-items gap-10">Product List</h4>
                <div class="d-flex flex-wrap gap-15">
                    <a class="btn btn-primary" href="{{ route('admin.products.create') }}">
                        <i class="ri-add-line me-1"></i> Add Product
                    </a>
                </div>
            </div>
            <div class="card-body pt-15">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price (LKR)</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-10">
                                        @if($product->image)
                                        <div class="avatar avatar-md radius-6">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 style="width:42px;height:42px;object-fit:cover;border-radius:6px;">
                                        </div>
                                        @else
                                        <div class="avatar avatar-md bg-primary-transparent text-primary radius-6"
                                             style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;border-radius:6px;">
                                            <i class="ri-image-line"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-medium">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ $product->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-transparent text-primary">
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        {{ number_format($product->price, 2) }}
                                        @if($product->old_price)
                                        <br><small class="text-muted text-decoration-line-through">{{ number_format($product->old_price, 2) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="badge bg-success-transparent text-success">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning-transparent text-warning">{{ $product->stock }} (Low)</span>
                                    @else
                                        <span class="badge bg-danger-transparent text-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success-transparent text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary-transparent text-secondary">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge bg-warning-transparent text-warning ms-1">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-8">
                                        <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                                           class="btn-icon btn-success-light" title="View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="btn-icon btn-info-light" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST" onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'?')">
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ri-box-3-line fs-32 d-block mb-10"></i>
                                    No products found.
                                    <a href="{{ route('admin.products.create') }}">Add your first product</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
        $('#dataTableDefault').DataTable({
            pageLength: 15,
            order: [[0, 'desc']],
            columnDefs: [{ orderable: false, targets: [6] }]
        });
    }
    $('[title]').tooltip();
});
</script>
@endpush
