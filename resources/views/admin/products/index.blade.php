@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Products</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="new-product-area pt-80 pb-50">
    <div class="container">

        {{-- Top Bar --}}
        <div class="row mb-30">
            <div class="col-md-6">
                <div class="section-title">
                    <h2>Manage Products</h2>
                </div>
            </div>
            <div class="col-md-6 text-end" style="padding-top:20px;">
                <a href="{{ route('admin.products.create') }}" class="btn btn-default login-btn">
                    <i class="fa fa-plus"></i> Add New Product
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="row mb-20">
            <div class="col-12">
                <div style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:4px;">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            </div>
        </div>
        @endif

        {{-- Products Grid --}}
        <div class="row">
            @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="single-new-product mt-40 category-new-product">
                    <div class="product-img">
                        <img src="{{ $product->image_url }}"
                             class="first_img" alt="{{ $product->name }}"
                             onerror="this.src='{{ asset('assets/images/product/1.jpg') }}'" />

                        @if($product->sale_price || $product->old_price)
                        @php $disc = $product->sale_price
                            ? round((($product->price - $product->sale_price) / $product->price) * 100)
                            : ($product->old_price ? round((($product->old_price - $product->price) / $product->old_price) * 100) : 0); @endphp
                        @if($disc > 0)<span class="new">{{ $disc }}%</span>@endif
                        @endif

                        <div class="new-product-action">
                            <a href="{{ route('admin.products.edit', $product->id) }}" title="Edit">
                                <span class="lnr lnr-pencil"></span>
                            </a>
                            <span style="color:#fff;padding:0 5px;">|</span>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                  style="display:inline;" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;color:#fff;" title="Delete">
                                    <span class="lnr lnr-trash"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="product-content text-center">
                        <h3>{{ Str::limit($product->name, 22) }}</h3>
                        <div style="font-size:12px;color:#999;margin-bottom:5px;">{{ $product->category ?? 'No Category' }}</div>
                        <div class="price">
                            @if($product->old_price || $product->sale_price)
                                <h4>LKR {{ number_format($product->price, 2) }}</h4>
                                <h3 class="del-price"><del>LKR {{ number_format($product->old_price ?? $product->sale_price, 2) }}</del></h3>
                            @else
                                <h4>LKR {{ number_format($product->price, 2) }}</h4>
                            @endif
                        </div>
                        <div style="margin-top:8px;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-default login-btn"
                               style="padding:5px 15px;font-size:12px;">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>No products yet.</h4>
                <a href="{{ route('admin.products.create') }}" class="btn btn-default login-btn mt-3">Add First Product</a>
            </div>
            @endforelse
        </div>

    </div>
</div>

@endsection
