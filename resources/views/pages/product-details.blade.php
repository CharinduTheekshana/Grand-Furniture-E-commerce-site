@extends('layouts.app')
@section('title', $product->name . ' - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Product Details</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="all-hyperion-page">
    <div class="container">
        <div class="row">

            {{-- LEFT col-md-9 --}}
            <div class="col-md-9">
                <div class="product-simple-area ptb-80">
                    <div class="row">

                        {{-- Image Gallery --}}
                        <div class="col-md-7">
                            <div class="tab-content">
                                <div class="tab-pane show active" id="view1">
                                    <a class="image-link" href="{{ $product->image_url }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                </div>
                                @php
                                    $thumbProducts = \App\Models\Product::where('is_active',true)
                                        ->where('id','!=',$product->id)
                                        ->inRandomOrder()->take(4)->get();
                                @endphp
                                @foreach($thumbProducts as $ti => $tp)
                                <div class="tab-pane" id="view{{ $ti+2 }}">
                                    <a class="image-link" href="{{ $tp->image_url }}">
                                        <img src="{{ $tp->image_url }}" alt="{{ $tp->name }}">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <ul class="nav sinple-tab-menu" role="tablist">
                                <li><a class="active" href="#view1" data-bs-toggle="tab">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" />
                                </a></li>
                                @foreach($thumbProducts as $ti => $tp)
                                <li><a href="#view{{ $ti+2 }}" data-bs-toggle="tab">
                                    <img src="{{ $tp->image_url }}" alt="{{ $tp->name }}" />
                                </a></li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Product Info --}}
                        <div class="col-md-5">
                            <div class="product-simple-content">
                                <div class="sinple-c-title"><h3>{{ $product->name }}</h3></div>
                                <div class="checkbox">
                                    @if($product->stock > 0)
                                        <span><i class="fa fa-check-square"></i> In stock</span>
                                    @else
                                        <span><i class="fa fa-times-circle"></i> Out of stock</span>
                                    @endif
                                </div>
                                <span>SKU:{{ strtoupper(substr(str_replace('-','',$product->slug),0,6)) }}</span>
                                <div class="product-price-star star-2">
                                    @php $avgRating = $reviews->count() > 0 ? round($reviews->avg(fn($r)=>($r->quality+$r->price+$r->value)/3)) : 3; @endphp
                                    @for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s > $avgRating ? '-o' : '' }}"></i>@endfor
                                    <span>({{ $reviews->count() }} Review{{ $reviews->count()!=1?'s':'' }})&nbsp;|&nbsp; Add Your Review</span>
                                </div>
                                <div class="price mt-2">
                                    @if($product->old_price)
                                        <h4>LKR {{ number_format($product->price,2) }}</h4>
                                        <h3 class="del-price"><del>LKR {{ number_format($product->old_price,2) }}</del></h3>
                                    @else
                                        <h4>LKR {{ number_format($product->price,2) }}</h4>
                                    @endif
                                </div>
                                <div class="quick-add-to-cart">
                                    <div class="numbers-row">
                                        <label for="qty">Qty:</label>
                                        <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}">
                                    </div>
                                    <button class="single_add_to_cart_button hyper-page add-to-cart" data-id="{{ $product->id }}" type="button">
                                        <span class="lnr lnr-cart"></span> Add to cart
                                    </button>
                                </div>
                                <div class="action-heiper">
                                    {{-- Sync → Checkout --}}
                                    @auth
                                    <a href="{{ route('checkout.index') }}"><span class="lnr lnr-sync"></span></a>
                                    @else
                                    <a href="#" class="checkout-guest-link"><span class="lnr lnr-sync"></span></a>
                                    @endauth
                                    <!-- {{-- Cart → Cart page --}}
                                    <a href="{{ route('cart.index') }}"><span class="lnr lnr-cart"></span></a> -->
                                    {{-- Wishlist --}}
                                    <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                                </div>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="product-info-detailed pb-80">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product-info-tab">
                                <ul class="nav product-info-tab-menu" role="tablist">
                                    <li><a class="active" href="#details" data-bs-toggle="tab">details</a></li>
                                    <li><a href="#reviews" id="reviews-tab" data-bs-toggle="tab">reviews {{ $reviews->count() }}</a></li>
                                </ul>
                                <div class="tab-content">
                                    {{-- Details Tab --}}
                                    <div class="tab-pane show active" id="details">
                                        <div class="product-info-tab-content">
                                            <p>{{ $product->description ?? 'Premium quality furniture crafted with care.' }}</p>
                                            <ul>
                                                <li>High quality materials.</li>
                                                <li>Durable and long lasting.</li>
                                                <li>Easy to assemble.</li>
                                                <li>Modern design fits any room.</li>
                                            </ul>
                                        </div>
                                    </div>

                                    {{-- Reviews Tab --}}
                                    <div class="tab-pane" id="reviews">
                                        @if(session('review_success'))
                                        <div class="alert alert-success mb-3">
                                            <i class="fa fa-check-circle"></i> {{ session('review_success') }}
                                        </div>
                                        @endif

                                        @forelse($reviews as $review)
                                        <div class="customer-review-top border-bottom pb-4 mb-4">
                                            <h4>{{ $review->nickname }}</h4>
                                            <div class="cus-review-left">
                                                <div class="single-customer-rating"><span>Quality</span>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->quality?'-o':'' }}"></i>@endfor</div>
                                                <div class="single-customer-rating"><span>Price</span>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->price?'-o':'' }}"></i>@endfor</div>
                                                <div class="single-customer-rating"><span>Value</span>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->value?'-o':'' }}"></i>@endfor</div>
                                            </div>
                                            <div class="cus-review-left">
                                                <p><strong>{{ $review->summary }}</strong></p>
                                                <p>{{ $review->review }}</p>
                                                <span>Review by {{ $review->nickname }} &nbsp; Posted on {{ $review->created_at->format('d/m/y') }}</span>
                                            </div>
                                        </div>
                                        @empty
                                        <p>No reviews yet. Be the first to review this product!</p>
                                        @endforelse

                                        <div class="customer-review-bottom fix">
                                            <h2>You're reviewing:</h2>
                                            <h2>{{ $product->name }}</h2>
                                            @auth
                                            <form action="{{ route('product.review',$product->slug) }}" method="POST">
                                                @csrf
                                                <p>Your Rating <span>*</span></p>
                                                <div class="cus-review-left mb-4">
                                                    @foreach(['quality'=>'Quality','price'=>'Price','value'=>'Value'] as $field=>$label)
                                                    <div class="single-customer-rating">
                                                        <span>{{ $label }}</span>
                                                        <div class="star-rating-input d-inline-block" data-field="{{ $field }}">
                                                            @for($s=1;$s<=5;$s++)
                                                            <i class="fa fa-star{{ $s>3?'-o':'' }}" data-value="{{ $s }}"></i>
                                                            @endfor
                                                        </div>
                                                        <input type="hidden" name="{{ $field }}" class="star-val-{{ $field }}" value="3">
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="form-group contuct_f"><label>Nickname <span>*</span></label><input type="text" name="nickname" class="form-control" value="{{ auth()->user()->name }}" required></div>
                                                <div class="form-group contuct_f"><label>Summary <span>*</span></label><input type="text" name="summary" class="form-control" placeholder="Brief summary" required></div>
                                                <div class="form-group contuct_f"><label>Review <span>*</span></label><textarea name="review" class="form-control" rows="4" required></textarea></div>
                                                <button type="submit" class="btn btn-default contact-btn">Submit Review</button>
                                            </form>
                                            @else
                                            <div class="alert alert-warning mt-3">
                                                <i class="fa fa-info-circle"></i> <a href="{{ route('login') }}">Login</a> to write a review.
                                            </div>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related Products --}}
                <div class="upsell-product dotted-style3">
                    <div class="upsell-product-title"><h3 class="text-uppercase">Related Products</h3></div>
                    <div class="row">
                        @forelse($relatedProducts as $r)
                        <div class="col-md-4">
                            <div class="single-new-product mt-30">
                                <div class="product-img">
                                    <a href="{{ route('product.show',$r->slug) }}"><img src="{{ $r->image_url }}" class="first_img" alt="{{ $r->name }}" /></a>
                                    <div class="new-product-action feature-action">
                                        <a href="{{ route('product.show',$r->slug) }}"><span class="lnr lnr-sync"></span></a>
                                        <a href="#" class="add-to-cart" data-id="{{ $r->id }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                                        <a href="#" class="wishlist-btn" data-id="{{ $r->id }}"><span class="lnr lnr-heart"></span></a>
                                    </div>
                                </div>
                                <div class="product-content text-center">
                                    <a href="{{ route('product.show',$r->slug) }}"><h3>{{ $r->name }}</h3></a>
                                    <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                    <h4>LKR {{ number_format($r->price,2) }}</h4>
                                </div>
                            </div>
                        </div>
                        @empty
                        @for($i=1;$i<=3;$i++)
                        <div class="col-md-4">
                            <div class="single-new-product mt-30">
                                <div class="product-img">
                                    <a href="{{ route('shop') }}"><img src="{{ asset('assets/images/product/'.$i.'.jpg') }}" class="first_img" alt="Product" /></a>
                                    <div class="new-product-action feature-action">
                                        <a href="{{ route('shop') }}"><span class="lnr lnr-sync"></span></a>
                                        <a href="{{ route('shop') }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                                        <a href="{{ route('shop') }}"><span class="lnr lnr-heart"></span></a>
                                    </div>
                                </div>
                                <div class="product-content text-center">
                                    <a href="{{ route('shop') }}"><h3>Beaumont Summit</h3></a>
                                    <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                    <h4>LKR 4,400.00</h4>
                                </div>
                            </div>
                        </div>
                        @endfor
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR col-md-3 --}}
            <div class="col-md-3">

                {{-- Banner --}}
                <div class="hyper-banner pt-80 pb-40">
                    <div class="single-banner">
                        <a href="{{ route('shop') }}"><img src="{{ asset('assets/images/banner/13.jpg') }}" alt="" /></a>
                    </div>
                </div>

                {{-- Bestseller --}}
                <div class="feature-preduct-area hyperion home-page-2 pb-50">
                    <div class="hyper-title"><h4 class="text-uppercase">bestseller</h4></div>
                    <div class="shop-sideber-active">
                        @php $bestsellers = \App\Models\Product::where('is_active',true)->where('id','!=',$product->id)->inRandomOrder()->take(3)->get(); @endphp
                        <div class="single-product-items">
                            @foreach($bestsellers as $b)
                            <div class="single-new-product">
                                <div class="product-img">
                                    <a href="{{ route('product.show',$b->slug) }}">
                                        <img src="{{ $b->image_url }}" class="first_img" alt="{{ $b->name }}" />
                                    </a>
                                </div>
                                <div class="product-content text-center">
                                    <a href="{{ route('product.show',$b->slug) }}"><h3>{{ Str::limit($b->name,18) }}</h3></a>
                                    <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                    <h4>LKR {{ number_format($b->price,2) }}</h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Cart Sidebar --}}
                @auth
                @php
                    $sCart  = \App\Models\CartItem::with('product')->where('user_id',auth()->id())->take(3)->get();
                    $sTotal = $sCart->sum(fn($i)=>($i->product->price??0)*$i->quantity);
                @endphp
                @if($sCart->count() > 0)
                <div class="bedroom-sideber mt-40">
                    <div class="bedroom-title text-uppercase"><h4>My Cart</h4></div>
                    @foreach($sCart as $item)
                    <div class="d-flex gap-2 py-2 border-bottom">
                        <img src="{{ $item->product->image_url }}" width="50" height="50"
                             class="object-fit-cover" alt="{{ $item->product->name }}">
                        <div>
                            <p class="small mb-0">{{ Str::limit($item->product->name,15) }}</p>
                            <small>{{ $item->quantity }} x LKR {{ number_format($item->product->price,0) }}</small>
                        </div>
                    </div>
                    @endforeach
                    <p class="mt-2 fw-bold">Total: LKR {{ number_format($sTotal,2) }}</p>
                    <a href="{{ route('checkout.index') }}" class="grand-btn d-block text-center mt-2">Checkout</a>
                </div>
                @endif
                @endauth

                {{-- Compare Products --}}
                <div class="bedroom-sideber mt-40">
                    <div class="bedroom-title text-uppercase"><h4>Compare Products</h4></div>
                    <p>You have no items to compare.</p>
                </div>

                {{-- My Wish List --}}
                <div class="bedroom-sideber mt-40">
                    <div class="bedroom-title text-uppercase"><h4>My Wish List</h4></div>
                    @auth
                        @php $wc = \App\Models\Wishlist::where('user_id',auth()->id())->count(); @endphp
                        @if($wc > 0)
                            <p>You have {{ $wc }} item(s). <a href="{{ route('wishlist.index') }}">View</a></p>
                        @else
                            <p>You have no items in your wish list.</p>
                        @endif
                    @else
                        <p>You have no items in your wish list.</p>
                    @endauth
                </div>

            </div>

        </div>
    </div>
</div>

<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div>
            <div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address"/><button><span class="lnr lnr-envelope"></span></button></form></div></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Add to Cart — qty from #qty input, handled by app.blade.php global handler
// Override qty for this page
$(document).on('click', '.hyper-page.add-to-cart', function(e) {
    e.preventDefault();
    var productId = $(this).data('id');
    var qty = parseInt($('#qty').val()) || 1;
    @auth
    $.post('/cart/add/' + productId, { qty: qty }, function(res) {
        if (res.redirect) { window.location.href = res.redirect; }
        else { $('.cart-count').text(res.count); showToast('Added to cart!', 'success'); }
    });
    @else
    saveIntendedAndLogin('cart', productId, qty);
    @endauth
});
$(document).on('mouseenter', '.star-rating-input i', function() {
    var val = $(this).data('value');
    $(this).closest('.star-rating-input').find('i').each(function(idx) {
        $(this).removeClass('fa-star fa-star-o').addClass(idx < val ? 'fa-star' : 'fa-star-o');
    });
});
$(document).on('mouseleave', '.star-rating-input', function() {
    var current = parseInt($(this).closest('.single-customer-rating').find('input[type=hidden]').val()) || 3;
    $(this).find('i').each(function(idx) {
        $(this).removeClass('fa-star fa-star-o').addClass(idx < current ? 'fa-star' : 'fa-star-o');
    });
});
$(document).on('click', '.star-rating-input i', function() {
    var val = $(this).data('value');
    $(this).closest('.single-customer-rating').find('input[type=hidden]').val(val);
    $(this).closest('.star-rating-input').find('i').each(function(idx) {
        $(this).removeClass('fa-star fa-star-o').addClass(idx < val ? 'fa-star' : 'fa-star-o');
    });
});
</script>
@endpush