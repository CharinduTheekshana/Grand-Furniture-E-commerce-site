@extends('layouts.app')
@section('title', $product->name . ' - Grand Furniture')

@push('styles')
<style>
/* Guarantee only the active image-gallery pane is visible, even if the
   theme's own tab script or CSS doesn't behave for some reason. */
.tab-content > .tab-pane { display: none !important; }
.tab-content > .tab-pane.active { display: block !important; }
</style>
@endpush

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
                                {{-- Cover photo — always the default view (matches what the
                                     customer clicked on in listings/cards). It has no
                                     corresponding thumbnail below when gallery photos exist,
                                     so the thumbnail count matches the color count. --}}
                                <div class="tab-pane show active" id="view1">
                                    <a class="image-link" href="{{ $product->image_url }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                </div>

                                {{-- Dedicated pane for whichever color photo was last clicked —
                                     works for this product's own colors AND for a "thumbnail
                                     product"'s colors (see selectColor() / thumbnail click JS). --}}
                                <div class="tab-pane" id="color-pane">
                                    <a class="image-link" href="#" id="color-pane-link">
                                        <img src="" alt="" id="color-pane-img">
                                    </a>
                                </div>

                                @foreach($product->images as $gi => $img)
                                <div class="tab-pane" id="gal{{ $gi+1 }}" data-color-id="{{ $img->color_id }}">
                                    <a class="image-link" href="{{ $img->image_url }}">
                                        <img src="{{ $img->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                </div>
                                @endforeach

                                @foreach($thumbProducts as $ti => $tp)
                                @php
                                    $tpColors = $tp->colors->map(function($c) use ($tp) {
                                        $img = $tp->images->firstWhere('color_id', $c->id);
                                        return [
                                            'id'    => $c->id,
                                            'name'  => $c->name,
                                            'code'  => $c->color_code,
                                            'image' => $img ? $img->image_url : '',
                                        ];
                                    })->values();
                                @endphp
                                <div class="tab-pane" id="view{{ $ti+2 }}"
                                    data-id="{{ $tp->id }}"
                                    data-name="{{ $tp->name }}"
                                    data-price="{{ number_format($tp->price,2) }}"
                                    data-old-price="{{ $tp->old_price ? number_format($tp->old_price,2) : '' }}"
                                    data-rating="{{ $tp->avgRating }}"
                                    data-description="{{ $tp->description }}"
                                    data-stock="{{ $tp->stock }}"
                                    data-url="{{ route('product.show', $tp->slug) }}"
                                    data-colors='{{ $tpColors->toJson() }}'>
                                    <a class="image-link" href="{{ $tp->image_url }}">
                                        <img src="{{ $tp->image_url }}" alt="{{ $tp->name }}">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <ul class="nav sinple-tab-menu" role="tablist">
                                {{-- Thumbnail #1 is always the main/cover photo (the same
                                     photo shown on listing cards) — matches what the customer
                                     clicked to get here. Thumbnails after it are this
                                     product's color/gallery photos. --}}
                                <li><a class="active" href="#view1">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" />
                                </a></li>
                                @foreach($product->images->take(4) as $gi => $img)
                                <li><a href="#gal{{ $gi+1 }}" data-color-id="{{ $img->color_id }}">
                                    <img src="{{ $img->image_url }}" alt="{{ $product->name }}" />
                                </a></li>
                                @endforeach
                                @foreach($thumbProducts as $ti => $tp)
                                <li><a href="#view{{ $ti+2 }}">
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

                                {{-- Offer Badge --}}
                                @if($product->is_offer_active && $product->stock > 0)
                                <div style="margin-bottom:10px;">
                                    <span class="product-offer-badge-detail {{ $product->offer_badge_class }}">
                                        {{ $product->offer_badge }}
                                    </span>
                                    @if($product->offer_type === 'flash_sale' && $product->offer_end_date)
                                    <div class="product-offer-countdown-detail">
                                        ⏱ Offer Ends In:
                                        <span class="countdown-timer" data-end="{{ $product->offer_end_date->toIso8601String() }}">
                                            --:--:--:--
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                {{-- Color Selection --}}
                                <div class="product-color-select" id="color-select-wrapper"
                                     style="margin-bottom:15px; {{ $product->colors->count() ? '' : 'display:none;' }}">
                                    <p style="margin-bottom:8px;font-weight:600;">
                                        Color:
                                        <span id="selected-color-name" style="color:#f60;font-weight:400;">
                                            — Select a color —
                                        </span>
                                    </p>
                                    <div id="color-swatch-row" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:8px;">
                                        @foreach($product->colors as $color)
                                        @php
                                            $colorImg = $product->images->firstWhere('color_id', $color->id);
                                        @endphp
                                        <div class="color-swatch"
                                            data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}"
                                            data-color-code="{{ $color->color_code ?? '' }}"
                                            data-image="{{ $colorImg ? $colorImg->image_url : '' }}"
                                            onclick="selectColor(this)"
                                            title="{{ $color->name }}"
                                            style="width:32px;height:32px;border-radius:50%;
                                                    background:{{ $color->color_code ?? '#ccc' }};
                                                    cursor:pointer;
                                                    border:3px solid transparent;
                                                    transition:all 0.2s;
                                                    box-shadow:0 2px 4px rgba(0,0,0,0.2);">
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="selected-color-input" value="">
                                </div>

                                @if($product->stock > 0)
                                <div class="quick-add-to-cart">
                                    <div class="numbers-row">
                                        <label for="qty">Qty:</label>
                                        <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}">
                                    </div>
                                    <button class="single_add_to_cart_button hyper-page add-to-cart"
                                            data-id="{{ $product->id }}" type="button">
                                        <span class="lnr lnr-cart"></span> Add to cart
                                    </button>
                                </div>
                                @else
                                <div class="quick-add-to-cart">
                                    <span style="display:inline-block;background:#e74c3c;color:#fff;
                                                padding:10px 24px;border-radius:4px;font-size:14px;
                                                font-weight:600;letter-spacing:0.5px;">
                                        <i class="fa fa-times-circle me-1"></i> Out of Stock
                                    </span>
                                    <p style="margin-top:8px;font-size:13px;color:#999;">
                                        This product is currently unavailable.
                                    </p>
                                </div>
                                @endif
                                <div class="action-heiper">
                                    @auth
                                    <a href="{{ route('checkout.index') }}"><span class="lnr lnr-sync"></span></a>
                                    @else
                                    <a href="#" class="checkout-guest-link" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                                    @endauth
                                    <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                                </div>
                                <p>{{ $product->description }}</p>

                                {{-- Brand / Manufacturer --}}
                                @if($product->brand)
                                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f0f0f0;">
                                    <span style="font-size:13px;color:#999;">Brand / Manufacturer:</span>
                                    <strong style="font-size:13px;color:#333;margin-left:6px;">{{ $product->brand }}</strong>
                                </div>
                                @endif

                                {{-- Category --}}
                                @if($product->category)
                                <div style="margin-top:6px;">
                                    <span style="font-size:13px;color:#999;">Category:</span>
                                    <a href="{{ route('shop', ['category' => $product->category->slug]) }}"
                                       style="font-size:13px;color:#c8a96e;margin-left:6px;">
                                        {{ $product->category->name }}
                                    </a>
                                </div>
                                @endif

                                {{-- Social Share --}}
                                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f0f0f0;">
                                    <span style="font-size:13px;color:#999;font-weight:600;margin-right:10px;">Share:</span>
                                    @php $shareUrl = urlencode(request()->url()); $shareTitle = urlencode($product->name); @endphp
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                       target="_blank" rel="noopener"
                                       style="display:inline-flex;align-items:center;justify-content:center;
                                              width:32px;height:32px;border-radius:50%;background:#1877f2;
                                              color:#fff;font-size:14px;margin-right:6px;text-decoration:none;">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                       target="_blank" rel="noopener"
                                       style="display:inline-flex;align-items:center;justify-content:center;
                                              width:32px;height:32px;border-radius:50%;background:#1da1f2;
                                              color:#fff;font-size:14px;margin-right:6px;text-decoration:none;">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                                       target="_blank" rel="noopener"
                                       style="display:inline-flex;align-items:center;justify-content:center;
                                              width:32px;height:32px;border-radius:50%;background:#25d366;
                                              color:#fff;font-size:14px;margin-right:6px;text-decoration:none;">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                    <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>showToast('Link copied!','success'))"
                                            style="display:inline-flex;align-items:center;justify-content:center;
                                                   width:32px;height:32px;border-radius:50%;background:#666;
                                                   color:#fff;font-size:14px;border:none;cursor:pointer;">
                                        <i class="fa fa-link"></i>
                                    </button>
                                </div>

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
                                    <li><a class="active" href="#details" data-bs-toggle="tab">Details</a></li>
                                    <li><a href="#specifications" data-bs-toggle="tab">Specifications</a></li>
                                    <li><a href="#reviews" id="reviews-tab" data-bs-toggle="tab">Reviews ({{ $reviews->count() }})</a></li>
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
                                    <div class="tab-pane" id="specifications">
                                        <div class="product-info-tab-content">
                                            <table style="width:100%;border-collapse:collapse;">
                                                <tbody>
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:10px 16px;font-weight:600;width:35%;background:#fafafa;color:#666;font-size:13px;">SKU</td>
                                                        <td style="padding:10px 16px;font-size:13px;">{{ strtoupper(substr(str_replace('-','',$product->slug),0,8)) }}</td>
                                                    </tr>
                                                    @if($product->brand)
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:10px 16px;font-weight:600;background:#fafafa;color:#666;font-size:13px;">Brand</td>
                                                        <td style="padding:10px 16px;font-size:13px;">{{ $product->brand }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($product->category)
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:10px 16px;font-weight:600;background:#fafafa;color:#666;font-size:13px;">Category</td>
                                                        <td style="padding:10px 16px;font-size:13px;">{{ $product->category->name }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($product->colors->count() > 0)
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:10px 16px;font-weight:600;background:#fafafa;color:#666;font-size:13px;">Available Colors</td>
                                                        <td style="padding:10px 16px;font-size:13px;">
                                                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                                                @foreach($product->colors as $c)
                                                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;">
                                                                    <span style="width:14px;height:14px;border-radius:50%;background:{{ $c->color_code ?? '#ccc' }};border:1px solid #ddd;display:inline-block;"></span>
                                                                    {{ $c->name }}
                                                                </span>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr style="border-bottom:1px solid #f0f0f0;">
                                                        <td style="padding:10px 16px;font-weight:600;background:#fafafa;color:#666;font-size:13px;">Availability</td>
                                                        <td style="padding:10px 16px;font-size:13px;">
                                                            @if($product->stock > 0)
                                                                <span style="color:#2ecc71;font-weight:600;">✓ In Stock ({{ $product->stock }} units)</span>
                                                            @else
                                                                <span style="color:#e74c3c;font-weight:600;">✗ Out of Stock</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
                    @foreach($bsPairs as $pair)
                    @php $bsA = $pair['a']; $bsB = $pair['b']; $bsRatingA = $pair['aRating']; $bsRatingB = $pair['bRating']; @endphp
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
                
                @if($hCart->count() > 0)
                <div class="bedroom-sideber mt-40">
                    <div class="bedroom-title text-uppercase"><h4>My Cart</h4></div>
                    @foreach($hCart as $item)
                    <div class="d-flex gap-2 py-2 border-bottom">
                        <img src="{{ $item->product->image_url }}" width="50" height="50"
                             class="object-fit-cover" alt="{{ $item->product->name }}">
                        <div>
                            <p class="small mb-0">{{ Str::limit($item->product->name,15) }}</p>
                            <small>{{ $item->quantity }} x LKR {{ number_format($item->product->price,0) }}</small>
                        </div>
                    </div>
                    @endforeach
                    <p class="mt-2 fw-bold">Total: LKR {{ number_format($hTotal,2) }}</p>
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
                        
                        @if($wishlistCount > 0)
                            <p>You have {{ $wishlistCount }} item(s). <a href="{{ route('wishlist.index') }}">View</a></p>
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


{{-- Recently Viewed Products --}}
<div class="recently-viewed-section" style="padding:50px 0;background:#fafafa;">
    <div class="container">
        <div class="section-title text-center mb-40">
            <h2 style="font-size:24px;font-weight:600;letter-spacing:0.5px;">Recently Viewed</h2>
        </div>
        <div class="row g-4 justify-content-center" id="recently-viewed-list">
            {{-- Populated by JS --}}
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

@php
    $originalColorsData = $product->colors->map(function($c) use ($product) {
        $img = $product->images->firstWhere('color_id', $c->id);
        return [
            'id'    => $c->id,
            'name'  => $c->name,
            'code'  => $c->color_code,
            'image' => $img ? $img->image_url : '',
        ];
    })->values();
@endphp

@push('scripts')
<script>
// This product's own colors, kept around so they can be restored after
// browsing a "thumbnail product"'s colors and clicking back to view1.
window.originalProductColors = @json($originalColorsData);

$(document).on('click', '.hyper-page.add-to-cart', function(e) {
    e.preventDefault();
    var productId = $(this).data('id');
    var qty = parseInt($('#qty').val()) || 1;
    var colorId = $('#selected-color-input').val() || '';
    @auth
    $.post('/cart/add/' + productId, { qty: qty, color_id: colorId }, function(res) {
        if (res.redirect) { window.location.href = res.redirect; }
        else { $('.cart-count').text(res.count); showToast('Added to cart!', 'success'); }
    });
    @else
    saveIntendedAndLogin('cart', productId, qty, colorId);
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

// Shared helper: activate one gallery pane + its thumbnail, deactivate the rest.
// Used by both color-swatch clicks and thumbnail clicks so there's a single
// source of truth — avoids the "two panes visible at once" bug.
function showGalleryPane(paneId) {
    $('.tab-content .tab-pane').removeClass('show active');
    $('#' + paneId).addClass('show active');
    $('.sinple-tab-menu a').removeClass('active');
    $('.sinple-tab-menu a[href="#' + paneId + '"]').addClass('active');
}

// Color swatch selection — works for this product's own colors AND for
// colors belonging to a "thumbnail product" (built dynamically, see below).
function selectColor(el) {
    document.querySelectorAll('.color-swatch').forEach(function(e) {
        e.style.border = '3px solid transparent';
        e.style.transform = 'scale(1)';
    });
    el.style.border = '3px solid #f60';
    el.style.transform = 'scale(1.2)';

    var colorId = el.dataset.colorId;
    document.getElementById('selected-color-input').value = colorId;
    document.getElementById('selected-color-name').textContent = el.dataset.colorName;

    var imgUrl = el.dataset.image;
    if (!imgUrl) return;

    document.getElementById('color-pane-img').src = imgUrl;
    document.getElementById('color-pane-img').alt = el.dataset.colorName;
    document.getElementById('color-pane-link').href = imgUrl;
    showGalleryPane('color-pane');
}

// Rebuild the color-swatch row for a given set of {id,name,code,image} colors.
// Used when switching to a "thumbnail product" so its own colors show up.
function renderColorSwatches(colors) {
    var $wrapper = $('#color-select-wrapper');
    var $row     = $('#color-swatch-row');

    if (!colors || !colors.length) {
        $wrapper.hide();
        return;
    }

    $row.empty();
    colors.forEach(function(c) {
        var $swatch = $('<div class="color-swatch"></div>')
            .attr('data-color-id', c.id)
            .attr('data-color-name', c.name)
            .attr('data-color-code', c.code || '')
            .attr('data-image', c.image || '')
            .attr('title', c.name)
            .attr('onclick', 'selectColor(this)')
            .css({
                width: '32px', height: '32px', borderRadius: '50%',
                background: c.code || '#ccc', cursor: 'pointer',
                border: '3px solid transparent', transition: 'all 0.2s',
                boxShadow: '0 2px 4px rgba(0,0,0,0.2)'
            });
        $row.append($swatch);
    });

    document.getElementById('selected-color-name').textContent = '— Select a color —';
    document.getElementById('selected-color-input').value = '';
    $wrapper.show();
}

// Thumbnail click → swap main image (+ product details, for "other product" thumbnails)
$(document).on('click', '.sinple-tab-menu a', function(e) {
    e.preventDefault();
    var tabId = $(this).attr('href').replace('#', ''); // e.g. view2

    showGalleryPane(tabId);

    // Find matching tab pane
    var $pane = $('#' + tabId);
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

    if (!name) {
        // view1 (this product's own cover) — restore this product's own colors
        if (tabId === 'view1') renderColorSwatches(window.originalProductColors);
        return;
    }

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

    // Show this "thumbnail product"'s own colors, if it has any
    var colors = $pane.data('colors');
    renderColorSwatches(colors);
});

// Recently Viewed — session storage
(function() {
    var key   = 'gf_recently_viewed';
    var limit = 6;
    var item  = {
        id:    {{ $product->id }},
        name:  @json(Str::limit($product->name, 25)),
        price: 'LKR {{ number_format($product->price, 2) }}',
        img:   @json($product->image_url),
        url:   '{{ route("product.show", $product->slug) }}'
    };

    var viewed = JSON.parse(localStorage.getItem(key) || '[]');
    viewed = viewed.filter(function(v) { return v.id !== item.id; });
    viewed.unshift(item);
    if (viewed.length > limit) viewed = viewed.slice(0, limit);
    localStorage.setItem(key, JSON.stringify(viewed));

    // Render recently viewed
    var container = document.getElementById('recently-viewed-list');
    if (!container) return;
    var others = viewed.filter(function(v) { return v.id !== item.id; });
    if (others.length === 0) {
        container.closest('.recently-viewed-section').style.display = 'none';
        return;
    }
    container.innerHTML = others.map(function(v) {
        return '<div class="col-lg-3 col-md-4 col-6">' +
            '<a href="' + v.url + '" class="rv-card" style="display:block;background:#fff;' +
                'border-radius:10px;overflow:hidden;text-decoration:none;' +
                'box-shadow:0 2px 10px rgba(0,0,0,0.06);transition:transform .2s ease,box-shadow .2s ease;">' +
                '<div style="aspect-ratio:1/1;background:#f7f5f2;display:flex;align-items:center;justify-content:center;padding:16px;">' +
                    '<img src="' + v.img + '" alt="' + v.name + '" ' +
                         'style="max-width:100%;max-height:100%;width:auto;height:auto;object-fit:contain;">' +
                '</div>' +
                '<div style="padding:14px 16px 18px;text-align:center;border-top:1px solid #f0eee9;">' +
                    '<h3 style="font-size:14px;font-weight:500;color:#2b2b2b;margin:0 0 6px;' +
                        'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + v.name + '</h3>' +
                    '<span style="font-size:14px;font-weight:600;color:#c8a96e;">' + v.price + '</span>' +
                '</div>' +
            '</a>' +
        '</div>';
    }).join('');

    // Subtle hover lift (delegated so it applies to freshly injected cards)
    $(document).off('mouseenter.rv mouseleave.rv', '.rv-card')
        .on('mouseenter.rv', '.rv-card', function() {
            $(this).css({ transform: 'translateY(-4px)', boxShadow: '0 8px 20px rgba(0,0,0,0.1)' });
        })
        .on('mouseleave.rv', '.rv-card', function() {
            $(this).css({ transform: 'translateY(0)', boxShadow: '0 2px 10px rgba(0,0,0,0.06)' });
        });
})();
</script>
@endpush