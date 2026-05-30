@extends('layouts.app')
@section('title','Shop - Grand Furniture')
@section('content')
<div class="page-title-wrapper"><div class="container"><div class="row"><div class="col-lg-12"><div class="page-title"><h3>Shop</h3></div></div></div></div></div>
<div class="bedroom-all-product-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="bedroom-sideber"><div class="bedroom-title text-uppercase"><h4>Shopping Options</h4></div></div>
                <div class="price-slider-area">
                    <h3 class="bedroom-side-title">Price</h3>
                    <div id="slider-range"></div>
                    <p><input type="text" id="amount" readonly style="border:0;color:#f6931f;font-weight:bold;"></p>
                    <form action="{{ route('shop') }}" method="GET" id="price-filter-form">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        <input type="hidden" name="min_price" id="min_price_input" value="{{ request('min_price',0) }}">
                        <input type="hidden" name="max_price" id="max_price_input" value="{{ request('max_price',$maxPrice) }}">
                        <button type="submit" style="margin-top:8px;padding:4px 14px;background:#333;color:#fff;border:none;cursor:pointer;">Filter</button>
                    </form>
                </div>
                <div class="category-area-start">
                    <div class="caregory"><h3 class="bedroom-side-title">Category</h3>
                        <ul>
                            <li><a href="{{ route('shop') }}" class="{{ !request('category') ? 'active' : '' }}">All <span>({{ $totalProducts }})</span></a></li>
                            @foreach($categories as $cat)
                            <li><a href="{{ route('shop',['category'=>$cat->slug]) }}" class="{{ request('category')==$cat->slug ? 'active' : '' }}">{{ $cat->name }} <span>({{ $cat->products_count }})</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="sideber-color mt-40"><h3 class="bedroom-side-title">Color</h3><ul><li><a href="#"></a></li><li class="bg-colo-3"><a href="#"></a></li><li class="bg-colo-4"><a href="#"></a></li><li class="bg-colo-5"><a href="#"></a></li><li class="bg-colo-6"><a href="#"></a></li></ul></div>
                <div class="bedroom-sideber mt-40"><div class="bedroom-title text-uppercase"><h4>My Wish List</h4></div>
                    @auth
                    @if($wishlistCount > 0)<p>You have <strong>{{ $wishlistCount }}</strong> item(s). <a href="{{ route('wishlist.index') }}">View</a></p>@else<p>You have no items in your wish list.</p>@endif
                    @else<p>You have no items in your wish list.</p>@endauth
                </div>
            </div>
            <div class="col-md-9">
                <div class="caregory-products-area">
                    <div class="row">
                        <div class="col-xl-2 col-md-3"><ul class="nav tab_menu"><li><a class="active" href="#viewed" data-bs-toggle="tab"><i class="fa fa-th"></i></a></li><li><a href="#random" data-bs-toggle="tab"><i class="fa fa-list"></i></a></li></ul></div>
                        <div class="col-xl-10 col-md-9">
                            <div class="product-option">
                                <div class="porduct-option-left floatleft"><span>Items {{ $products->firstItem()??0 }}–{{ $products->lastItem()??0 }} of {{ $products->total() }}</span></div>
                                <div class="product-option-right floatright">
                                    <form action="{{ route('shop') }}" method="GET" id="sort-form">
                                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                                        <div class="sort-by"><label>Sort By:</label><select class="cust-select" name="sort" onchange="document.getElementById('sort-form').submit()"><option value="" {{ !request('sort')?'selected':'' }}>Position</option><option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Newest</option><option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Price: Low to High</option><option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Price: High to Low</option></select></div>
                                        <div class="sort-by"><label>Show:</label><select class="cust-select cust-select-2" name="per_page" onchange="document.getElementById('sort-form').submit()"><option value="12" {{ request('per_page',12)==12?'selected':'' }}>12</option><option value="24" {{ request('per_page')==24?'selected':'' }}>24</option><option value="36" {{ request('per_page')==36?'selected':'' }}>36</option></select></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="viewed">
                            <div class="row">
                                @forelse($products as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="single-new-product mt-40 category-new-product">
                                        <div class="product-img">
                                            <a href="{{ route('product.show',$product->slug) }}"><img src="{{ $product->image_url }}" class="first_img" alt="{{ $product->name }}" /></a>
                                            @if($product->old_price) @php $d=round(($product->old_price-$product->price)/$product->old_price*100); @endphp @if($d>0)<span class="new">{{ $d }}%</span>@endif @endif
                                            <div class="new-product-action">
                                                <a href="#" class="home-checkout-btn" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                                                <a href="#" class="add-to-cart" data-id="{{ $product->id }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                                                <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                                            </div>
                                        </div>
                                        <div class="product-content text-center">
                                            <a href="{{ route('product.show',$product->slug) }}"><h3>{{ $product->name }}</h3></a>
                                            <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                            <div class="price">
                                                @if($product->old_price)<h4>LKR {{ number_format($product->price,2) }}</h4><h3 class="del-price"><del>LKR {{ number_format($product->old_price,2) }}</del></h3>
                                                @else<h4>LKR {{ number_format($product->price,2) }}</h4>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5"><h4>No products found.</h4><a href="{{ route('shop') }}" class="btn btn-default login-btn mt-3">Clear Filters</a></div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane" id="random">
                            @forelse($products as $product)
                            <div class="row mt-30" style="border-bottom:1px solid #eee;padding-bottom:20px;margin-bottom:20px;">
                                <div class="col-md-3"><a href="{{ route('product.show',$product->slug) }}"><img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}" /></a></div>
                                <div class="col-md-9"><a href="{{ route('product.show',$product->slug) }}"><h3>{{ $product->name }}</h3></a><div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div><h4 class="mt-2">LKR {{ number_format($product->price,2) }}</h4><p class="mt-2">{{ Str::limit($product->description,100) }}</p><a href="#" class="add-to-cart btn btn-default login-btn mt-2" data-id="{{ $product->id }}" style="font-size:13px;padding:6px 16px;"><span class="lnr lnr-cart"></span> Add to Cart</a></div>
                            </div>
                            @empty<div class="text-center py-5"><h4>No products found.</h4></div>@endforelse
                        </div>
                    </div>
                    <div class="text-center mt-40">{{ $products->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function(){
    var min={{ request('min_price',$minPrice) }},max={{ request('max_price',$maxPrice) }};
    $("#slider-range").slider({range:true,min:{{ $minPrice }},max:{{ $maxPrice }},values:[min,max],slide:function(e,ui){$("#amount").val("LKR "+ui.values[0].toLocaleString()+" - LKR "+ui.values[1].toLocaleString());$("#min_price_input").val(ui.values[0]);$("#max_price_input").val(ui.values[1]);}});
    $("#amount").val("LKR "+$("#slider-range").slider("values",0).toLocaleString()+" - LKR "+$("#slider-range").slider("values",1).toLocaleString());
});
</script>
@endpush
