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
@php $tpRating = (int) round(\App\Models\Review::where('product_id',$tp->id)->avg(\DB::raw('(quality+price+value)/3')) ?? 3); @endphp
<div class="tab-pane" id="view{{ $ti+2 }}"
    data-id="{{ $tp->id }}"
    data-name="{{ $tp->name }}"
    data-price="{{ number_format($tp->price,2) }}"
    data-old-price="{{ $tp->old_price ? number_format($tp->old_price,2) : '' }}"
    data-rating="{{ $tpRating }}"
    data-description="{{ $tp->description }}"
    data-stock="{{ $tp->stock }}"
    data-url="{{ route('product.show', $tp->slug) }}">
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
                                    @auth
                                    <a href="{{ route('checkout.index') }}"><span class="lnr lnr-sync"></span></a>
                                    @else
                                    <a href="#" class="checkout-guest-link" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                                    @endauth
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

                {{-- Bestseller: 1 column, 3 cards visible, 2 pages (3+3), arrows + keyboard nav, hover swaps product --}}
                @php
                    $bsPool  = \App\Models\Product::where('is_active', true)
                        ->where('id', '!=', $product->id)
                        ->inRandomOrder()->get()->values();
                    $bsCount = $bsPool->count();

                    // 6 pairs: A = display, B = hover (different product)
                    $bsPairs = [];
                    for ($bsI = 0; $bsI < min(6, $bsCount); $bsI++) {
                        $bsA    = $bsPool[$bsI];
                        $bsBIdx = ($bsI + 6) % $bsCount;
                        if ($bsPool[$bsBIdx]->id === $bsA->id) $bsBIdx = ($bsBIdx + 1) % $bsCount;
                        $bsPairs[] = [$bsA, $bsPool[$bsBIdx]];
                    }
                @endphp

                <style>
                .bs-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    border-bottom: 2px solid #c8a96e;
                    padding-bottom: 10px;
                    margin-bottom: 16px;
                }
                .bs-header h4 {
                    font-size: 16px;
                    font-weight: 800;
                    letter-spacing: 1px;
                    margin: 0;
                    color: #111;
                }
                .bs-arrows {
                    display: flex;
                    align-items: center;
                    gap: 2px;
                    color: #bbb;
                    font-size: 13px;
                }
                .bs-arrows button {
                    background: none;
                    border: none;
                    font-size: 20px;
                    line-height: 1;
                    cursor: pointer;
                    color: #888;
                    padding: 0 2px;
                    transition: color 0.2s;
                }
                .bs-arrows button:hover { color: #c8a96e; }
                /* Single column, 3 cards stacked */
                #bsGrid { display: flex; flex-direction: column; gap: 12px; }
                .bs-card {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    border: 1px solid #e8e8e8;
                    background: #fff;
                    overflow: hidden;
                    transition: box-shadow 0.2s;
                    cursor: pointer;
                }
                .bs-card:hover { box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
                .bs-card-img {
                    flex-shrink: 0;
                    width: 100px;
                    height: 100px;
                    overflow: hidden;
                    background: #f4f4f4;
                    position: relative;
                }
                .bs-card-img img {
                    position: absolute;
                    top: 0; left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                    transition: opacity 0.35s ease, transform 0.35s ease;
                }
                .bs-card-img .bs-img-first  { opacity: 1; transform: scale(1);    z-index: 1; }
                .bs-card-img .bs-img-second { opacity: 0; transform: scale(1.06); z-index: 2; }
                .bs-card:hover .bs-card-img .bs-img-first  { opacity: 0; transform: scale(1.06); }
                .bs-card:hover .bs-card-img .bs-img-second { opacity: 1; transform: scale(1);    }
                .bs-card-body {
                    flex: 1;
                    padding: 10px 14px;
                    position: relative;
                    min-height: 100px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .bs-details {
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    padding: 10px 14px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 4px;
                    transition: opacity 0.35s ease;
                }
                .bs-details-first  { opacity: 1; }
                .bs-details-second { opacity: 0; }
                .bs-card:hover .bs-details-first  { opacity: 0; }
                .bs-card:hover .bs-details-second { opacity: 1; }
                .bs-card-body a { text-decoration: none; }
                .bs-card-name {
                    font-size: 13px;
                    font-weight: 600;
                    color: #222;
                    margin: 0;
                    line-height: 1.3;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    transition: color 0.2s;
                }
                .bs-card:hover .bs-details-second .bs-card-name { color: #c8a96e; }
                .bs-card-stars .fa { font-size: 11px; color: #c8a96e; }
                .bs-card-stars .fa-star-o { color: #ddd; }
                .bs-card-price { font-size: 13px; font-weight: 700; color: #333; }
                </style>

                <div class="bs-sidebar pb-50">
                    <div class="bs-header">
                        <h4>BESTSELLER</h4>
                        <div class="bs-arrows">
                            <button id="bsPrev" onclick="bsNav(-1)">&#8249;</button>
                            <span>/</span>
                            <button id="bsNext" onclick="bsNav(1)">&#8250;</button>
                        </div>
                    </div>
                    <div id="bsGrid"></div>
                </div>

                <script>
                var bsData = [
                    @foreach($bsPairs as [$bsA, $bsB])
                    @php
                        $bsRatingA = (int) round(\App\Models\Review::where('product_id',$bsA->id)->avg(\DB::raw('(quality+price+value)/3')) ?? 3);
                        $bsRatingB = (int) round(\App\Models\Review::where('product_id',$bsB->id)->avg(\DB::raw('(quality+price+value)/3')) ?? 3);
                    @endphp
                    {
                        a: { name: @json(Str::limit($bsA->name,22)), url: "{{ route('product.show',$bsA->slug) }}", img: @json($bsA->image_url), price: "LKR {{ number_format($bsA->price,2) }}", rating: {{ $bsRatingA }} },
                        b: { name: @json(Str::limit($bsB->name,22)), url: "{{ route('product.show',$bsB->slug) }}", img: @json($bsB->image_url), price: "LKR {{ number_format($bsB->price,2) }}", rating: {{ $bsRatingB }} }
                    },
                    @endforeach
                ];

                var bsPage = 0;
                var BS_PER = 3;

                function bsPages() { return Math.max(1, Math.ceil(bsData.length / BS_PER)); }

                function bsStars(r) {
                    var s = '';
                    for (var i = 1; i <= 5; i++)
                        s += '<i class="fa fa-star' + (i > r ? '-o' : '') + '"></i>';
                    return s;
                }

                function bsCard(pair) {
                    var a = pair.a, b = pair.b;
                    return '<div class="bs-card" onclick="window.location=\'' + a.url + '\'">' +
                        '<a href="' + a.url + '" class="bs-card-img">' +
                            '<img src="' + a.img + '" class="bs-img-first" alt="' + a.name + '">' +
                            '<img src="' + b.img + '" class="bs-img-second" alt="' + b.name + '">' +
                        '</a>' +
                        '<div class="bs-card-body">' +
                            '<div class="bs-details bs-details-first">' +
                                '<a href="' + a.url + '"><div class="bs-card-name">' + a.name + '</div></a>' +
                                '<div class="bs-card-stars">' + bsStars(a.rating) + '</div>' +
                                '<span class="bs-card-price">' + a.price + '</span>' +
                            '</div>' +
                            '<div class="bs-details bs-details-second">' +
                                '<a href="' + b.url + '"><div class="bs-card-name">' + b.name + '</div></a>' +
                                '<div class="bs-card-stars">' + bsStars(b.rating) + '</div>' +
                                '<span class="bs-card-price">' + b.price + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                }

                function bsRender() {
                    var slice = bsData.slice(bsPage * BS_PER, bsPage * BS_PER + BS_PER);
                    document.getElementById('bsGrid').innerHTML = slice.map(bsCard).join('');
                }

                function bsNav(dir) {
                    bsPage = (bsPage + dir + bsPages()) % bsPages();
                    bsRender();
                }

                document.addEventListener('keydown', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown')  { e.preventDefault(); bsNav(1); }
                    if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')    { e.preventDefault(); bsNav(-1); }
                });

                document.addEventListener('contextmenu', function() { bsPage = 0; bsRender(); });

                bsRender();
                </script>

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

// Thumbnail click → swap main image + all product details
$(document).on('click', '.sinple-tab-menu a', function() {
    var tabId = $(this).attr('href'); // e.g. #view2

    // Find matching tab pane
    var $pane = $(tabId);
    if (!$pane.length) return;

    // Get product data stored on the pane
    var name        = $pane.data('name');
    var price       = $pane.data('price');
    var oldPrice    = $pane.data('old-price');
    var rating      = $pane.data('rating');
    var description = $pane.data('description');
    var productId   = $pane.data('id');
    var productUrl  = $pane.data('url');
    var stock       = $pane.data('stock');

    if (!name) return; // view1 (current product) — no change needed

    // Update name
    $('.sinple-c-title h3').text(name);

    // Update price
    if (oldPrice) {
        $('.price h4').text('LKR ' + price);
        $('.price .del-price del').text('LKR ' + oldPrice);
    } else {
        $('.price h4').text('LKR ' + price);
        $('.price .del-price').hide();
    }

    // Update description
    $('.product-simple-content > p').text(description);

    // Update rating stars
    var $stars = $('.product-price-star.star-2');
    $stars.find('i').remove();
    for (var i = 1; i <= 5; i++) {
        $stars.prepend('<i class="fa fa-star' + (i > rating ? '-o' : '') + '"></i>');
    }

    // Update stock
    if (stock > 0) {
        $('.checkbox span').html('<i class="fa fa-check-square"></i> In stock');
    } else {
        $('.checkbox span').html('<i class="fa fa-times-circle"></i> Out of stock');
    }

    // Update add to cart button data-id
    $('.single_add_to_cart_button').data('id', productId);
    $('.wishlist-btn').data('id', productId);
    $('.checkout-guest-link').data('id', productId);

    // Update product URL (sync button for auth users)
    $('a[href*="checkout"]').not('.checkout-guest-link').attr('href', productUrl);
});
</script>
@endpush