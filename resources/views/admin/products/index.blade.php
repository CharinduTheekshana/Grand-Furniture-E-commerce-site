@extends('layouts.admin')
@section('title', 'Products')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Product List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Product List</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

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
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Product List</h4>
                <a class="btn btn-primary" href="{{ route('admin.products.create') }}">
                    <i class="ri-add-line me-1"></i> Add Product
                </a>
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
                                <th>Colors</th>
                                <th>Offer</th>
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
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             style="width:42px;height:42px;object-fit:cover;border-radius:6px;">
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
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="badge bg-success-transparent text-success">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning-transparent text-warning">{{ $product->stock }}</span>
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
                                </td>
                                <td>
                                    <div class="d-flex gap-4 flex-wrap">
                                        @foreach($product->colors as $clr)
                                        <span title="{{ $clr->name }}"
                                              data-bs-toggle="tooltip"
                                              style="display:inline-block;width:20px;height:20px;
                                                     border-radius:50%;
                                                     background:{{ $clr->color_code ?? '#ccc' }};
                                                     border:1px solid #ddd;">
                                        </span>
                                        @endforeach
                                        @if($product->colors->isEmpty())
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->offer_badge && $product->offer_status)
                                        <span style="display:inline-block;background:#f0f0f0;
                                                     border-radius:4px;padding:3px 8px;font-size:11px;
                                                     font-weight:600;max-width:110px;overflow:hidden;
                                                     text-overflow:ellipsis;white-space:nowrap;"
                                              title="{{ $product->offer_badge }}">
                                            {{ Str::limit($product->offer_badge, 14) }}
                                        </span>
                                        @if($product->is_offer_active)
                                            <br><small style="color:#16a34a;font-size:10px;">● Active</small>
                                        @else
                                            <br><small style="color:#dc2626;font-size:10px;">● Expired</small>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
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
                                              method="POST"
                                              onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger-light">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No products found.
                                    <a href="{{ route('admin.products.create') }}">Add first product</a>
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
            columnDefs: [{ orderable: false, targets: [6, 7] }]
        });
    }
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush