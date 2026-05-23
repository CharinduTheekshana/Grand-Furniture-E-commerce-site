@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Admin Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stat Boxes --}}
<div class="new-product-area pt-80 pb-50">
    <div class="container">
        <div class="section-title text-center">
            <h2>Overview</h2>
        </div>
        <div class="row">
            <div class="col-md-3 mar_b-30">
                <div class="single-new-product" style="padding:25px;border:1px solid #ebebeb;text-align:center;">
                    <div class="product-content">
                        <span class="lnr lnr-cart" style="font-size:36px;color:#f6931f;"></span>
                        <h2 style="font-size:40px;font-weight:700;margin:10px 0;">{{ $productCount }}</h2>
                        <h3>Products</h3>
                        <a href="{{ route('admin.products.index') }}" style="font-size:13px;">Manage Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mar_b-30">
                <div class="single-new-product" style="padding:25px;border:1px solid #ebebeb;text-align:center;">
                    <div class="product-content">
                        <span class="lnr lnr-pencil" style="font-size:36px;color:#f6931f;"></span>
                        <h2 style="font-size:40px;font-weight:700;margin:10px 0;">{{ $blogCount }}</h2>
                        <h3>Blog Posts</h3>
                        <a href="{{ route('admin.blog.index') }}" style="font-size:13px;">Manage Blog</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mar_b-30">
                <div class="single-new-product" style="padding:25px;border:1px solid #ebebeb;text-align:center;">
                    <div class="product-content">
                        <span class="lnr lnr-envelope" style="font-size:36px;color:#f6931f;"></span>
                        <h2 style="font-size:40px;font-weight:700;margin:10px 0;">{{ $contactCount }}</h2>
                        <h3>Contact Messages</h3>
                        <a href="{{ route('admin.contacts.index') }}" style="font-size:13px;">View Submissions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mar_b-30">
                <div class="single-new-product" style="padding:25px;border:1px solid #ebebeb;text-align:center;">
                    <div class="product-content">
                        <span class="lnr lnr-users" style="font-size:36px;color:#f6931f;"></span>
                        <h2 style="font-size:40px;font-weight:700;margin:10px 0;">{{ $userCount }}</h2>
                        <h3>Registered Users</h3>
                        <a href="{{ url('/') }}" style="font-size:13px;">View Store</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row mt-40">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>Quick Actions</h2>
                </div>
            </div>
            <div class="col-md-4 mar_b-30 text-center">
                <a href="{{ route('admin.products.create') }}" class="btn btn-default login-btn">
                    <i class="fa fa-plus"></i> Add New Product
                </a>
            </div>
            <div class="col-md-4 mar_b-30 text-center">
                <a href="{{ route('admin.blog.create') }}" class="btn btn-default login-btn">
                    <i class="fa fa-plus"></i> Write Blog Post
                </a>
            </div>
            <div class="col-md-4 mar_b-30 text-center">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-default login-btn">
                    <i class="fa fa-eye"></i> View Store
                </a>
            </div>
        </div>

        {{-- Recent Products --}}
        @if($recentProducts->count() > 0)
        <div class="row mt-40">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>Recent Products</h2>
                </div>
            </div>
            @foreach($recentProducts as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="single-new-product mt-40">
                    <div class="product-img">
                        <img src="{{ $product->image_url }}"
                             class="first_img" alt="{{ $product->name }}"
                             onerror="this.src='https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}'" />
                        <div class="new-product-action">
                            <a href="{{ route('admin.products.edit', $product->id) }}">
                                <span class="lnr lnr-pencil"></span>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                  style="display:inline;" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;color:#fff;">
                                    <span class="lnr lnr-trash"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="product-content text-center">
                        <h3>{{ Str::limit($product->name, 20) }}</h3>
                        <h4>LKR {{ number_format($product->price, 2) }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- Newsletter --}}
<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30">
                <div class="contuct-info text-center">
                    <h4>Grand Furniture Admin</h4>
                    <p>Manage your store from here</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 offset-lg-1">
                <div class="search-box">
                    <form action="{{ route('admin.products.index') }}" method="GET">
                        <input type="text" name="q" placeholder="Search products..." />
                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
