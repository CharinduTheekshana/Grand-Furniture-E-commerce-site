@extends('layouts.app')
@section('title', 'Grand | Home')
@section('content')

{{-- Load all products once (prevents N+1 queries) --}}
@php
    $allProducts = \App\Models\Product::where('is_active', true)->get();
    // Shuffle for row 2 — different order
    $newProductsRow2 = $newProducts->shuffle();
@endphp

{{-- ═══ SLIDER ═══ --}}
<div class="slider-area">
    <div class="slider_img">
        <img src="{{ asset('assets/images/slider/1.jpg') }}" alt="" title="#caption1">
        <img src="{{ asset('assets/images/slider/2.jpg') }}" alt="" title="#caption2">
    </div>
    <div id="caption1" class="nivo-html-caption">
        <div class="slide_all_1">
            <h1 class="wow bounceInUp" data-wow-delay=".3s" data-wow-duration=".9s">Furniture sale</h1>
            <h3 class="wow bounceInUp" data-wow-delay=".5s" data-wow-duration="1.1s">up to 25% off</h3>
            <h2 class="wow bounceInUp" data-wow-delay=".6s" data-wow-duration="1.2s">furniture brands</h2>
            <div class="slider-btn wow bounceInUp" data-wow-delay=".7s" data-wow-duration="1.3s">
                <a href="{{ route('shop') }}">view more</a>
            </div>
        </div>
    </div>
    <div id="caption2" class="nivo-html-caption">
        <div class="slide_all_2">
            <h1 class="wow fadeInLeft" data-wow-delay=".4s" data-wow-duration="1.1s">summer sale</h1>
            <h3 class="wow bounceInUp" data-wow-delay=".5s" data-wow-duration="1.1s">up to 25% off</h3>
            <h2 class="wow fadeInRight" data-wow-delay=".8s" data-wow-duration="1.5s">furniture brands</h2>
            <div class="slider-btn wow bounceInUp" data-wow-delay=".7s" data-wow-duration="1.3s">
                <a href="{{ route('shop') }}">view more</a>
            </div>
        </div>
    </div>
</div>

{{-- ═══ SERVICE ICONS (category navigation) ═══ --}}
<div class="service-area pt-80">
    <div class="container">
        <div class="row-wrapper service-active">
            @foreach([
                ['bedroom',     'Bedroom',     '2','1'],
                ['living-room', 'Living Room', '4','3'],
                ['dining-room', 'Dining Room', '6','5'],
                ['sofa',        'Sofa',        '8','7'],
                ['chair',       'chair',       '10','9'],
                ['armchair',    'armchair',    '12','11'],
            ] as [$slug, $label, $p, $s])
            <div class="custom-col">
                <div class="single-service">
                    <div class="service-img">
                        <a href="{{ route('shop', ['category' => $slug]) }}"><img src="{{ asset('assets/images/service/'.$p.'.png') }}" class="primary" alt="{{ $label }}" /></a>
                        <a href="{{ route('shop', ['category' => $slug]) }}"><img src="{{ asset('assets/images/service/'.$s.'.png') }}" class="secendary" alt="{{ $label }}" /></a>
                    </div>
                    <h3>{{ $label }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══ NEW PRODUCTS (carousel row 1 + row 2) ═══ --}}
<div class="new-product-area pt-80 pb-50">
    <div class="container">
        <div class="section-title text-center">
            <h2>New Products</h2>
            <p>Browse the collection of our new products, You'll definitely find what you <br /> are looking for.</p>
        </div>

        {{-- Macro: product card with hover second image --}}
        @foreach([$newProducts, $newProductsRow2] as $productList)
        <div class="row-wrapper product-carousel-active">
            @forelse($productList as $product)
            <div class="custom-col">
                <div class="single-new-product" data-product-id="{{ $product->id }}">
                    <div class="product-img">
                        <a href="{{ route('product.show', $product->slug) }}">
                            {{-- first_img: current product --}}
                            <img src="{{ $product->image_url }}" class="first_img" alt="{{ $product->name }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ number_format($product->price, 2) }}"
data-old-price="{{ $product->old_price ? number_format($product->old_price, 2) : '' }}"
                                data-url="{{ route('product.show', $product->slug) }}" />
                            {{-- seceond_img: another product in same category (hover effect) --}}
                            @php $second = $allProducts->where('category_id', $product->category_id)->where('id', '!=', $product->id)->shuffle()->first(); if(!$second) $second = $allProducts->where('id', '!=', $product->id)->shuffle()->first(); @endphp
                            <img src="{{ $second ? $second->image_url : $product->image_url }}" class="seceond_img" alt=""
                                data-name="{{ $second ? $second->name : $product->name }}"
                                data-price="{{ $second ? number_format($second->price, 2) : number_format($product->price, 2) }}"
data-old-price="{{ $second ? ($second->old_price ? number_format($second->old_price, 2) : '') : ($product->old_price ? number_format($product->old_price, 2) : '') }}"
                                data-url="{{ $second ? route('product.show', $second->slug) : route('product.show', $product->slug) }}" />
                        </a>
                        {{-- Sale badge --}}
                        @if($product->old_price)
                            @php $disc = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                            @if($disc > 0)<span class="new">{{ $disc }}%</span>@endif
                        @endif
                        <div class="new-product-action">
                            <a href="#" class="home-checkout-btn" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                            <a href="#" class="add-to-cart" data-id="{{ $product->id }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                            <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                        </div>
                    </div>
                    <div class="product-content text-center">
                        <a href="{{ route('product.show', $product->slug) }}" class="hover-product-url"><h3 class="hover-product-name">{{ $product->name }}</h3></a>
                        <div class="product-price-star">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        </div>
                        <div class="price">
                            @if($product->old_price)
                                <h4 class="hover-product-price">LKR {{ number_format($product->price, 2) }}</h4>
                                <h3 class="del-price hover-product-old-price"><del>LKR {{ number_format($product->old_price, 2) }}</del></h3>
                            @else
                                <h4 class="hover-product-price">LKR {{ number_format($product->price, 2) }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Fallback static images if no products in DB --}}
            @for($i = 1; $i <= 5; $i++)
            <div class="custom-col">
                <div class="single-new-product">
                    <div class="product-img">
                        <a href="{{ route('shop') }}">
                            <img src="{{ asset('assets/images/product/'.$i.'.jpg') }}" class="first_img" alt="Product" />
                            <img src="{{ asset('assets/images/product/'.($i+1).'.jpg') }}" class="seceond_img" alt="" />
                        </a>
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
        @endforeach
    </div>
</div>

{{-- ═══ BANNERS ═══ --}}
<div class="banner-area pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-banner mar_b-30"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/banner/1.jpg') }}" alt="" /></a></div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="row">
                    <div class="col-lg-12"><div class="single-banner banner_img_3"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/banner/2.jpg') }}" alt="" /></a></div></div>
                    <div class="col-lg-12"><div class="single-banner"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/banner/4.jpg') }}" alt="" /></a></div></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-banner"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/banner/3.jpg') }}" alt="" /></a></div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ TOP INTERESTING (with hover second image) ═══ --}}
<div class="top-interesting-area dotted-style2">
    <div class="container">
        <div class="section-title text-center">
            <h2>TOP INTERESTING</h2>
            <p>Browse the collection of our new products, You'll definitely find what you <br /> are looking for.</p>
        </div>
        <div class="row-wrapper top-interesting-active">
            @forelse($topInteresting as $product)
            <div class="custom-col">
                <div class="single-new-product" data-product-id="{{ $product->id }}">
                    <div class="product-img">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image_url }}" class="first_img" alt="{{ $product->name }}"
    data-name="{{ $product->name }}"
    data-price="{{ number_format($product->price, 2) }}"
data-old-price="{{ $product->old_price ? number_format($product->old_price, 2) : '' }}"
    data-url="{{ route('product.show', $product->slug) }}" />
                            @php $second = $allProducts->where('category_id', $product->category_id)->where('id', '!=', $product->id)->shuffle()->first(); if(!$second) $second = $allProducts->where('id', '!=', $product->id)->shuffle()->first(); @endphp
                            <img src="{{ $second ? $second->image_url : $product->image_url }}" class="seceond_img" alt=""
    data-name="{{ $second ? $second->name : $product->name }}"
    data-price="{{ $second ? number_format($second->price, 2) : number_format($product->price, 2) }}"
data-old-price="{{ $second ? ($second->old_price ? number_format($second->old_price, 2) : '') : ($product->old_price ? number_format($product->old_price, 2) : '') }}"
    data-url="{{ $second ? route('product.show', $second->slug) : route('product.show', $product->slug) }}" />
                        </a>
                    </div>
                    <div class="product-content text-center">
                        <a href="{{ route('product.show', $product->slug) }}" class="hover-product-url"><h3 class="hover-product-name">{{ $product->name }}</h3></a>
                        <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                        <div class="price">
                            <h4 class="hover-product-price">LKR {{ number_format($product->price, 2) }}</h4>
                            @if($product->old_price)<h3 class="del-price hover-product-old-price"><del>LKR {{ number_format($product->old_price, 2) }}</del></h3>@endif
                        </div>
                    </div>
                    <div class="product-icon-wrapper">
                        <div class="product-icon">
                            <ul>
                                <li><a href="#" class="home-checkout-btn" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a></li>
                                <li><a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a></li>
                                <li><a href="#" class="add-to-cart" data-id="{{ $product->id }}"><span class="lnr lnr-cart"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @for($i=1;$i<=6;$i++)
            <div class="custom-col">
                <div class="single-new-product">
                    <div class="product-img">
                        <a href="{{ route('shop') }}">
                            <img src="{{ asset('assets/images/product/'.$i.'.jpg') }}" class="first_img" alt="" />
                            <img src="{{ asset('assets/images/product/'.($i+1).'.jpg') }}" class="seceond_img" alt="" />
                        </a>
                    </div>
                    <div class="product-content text-center">
                        <a href="{{ route('shop') }}"><h3>Beaumont Summit</h3></a>
                        <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                        <div class="price"><h4>LKR 4,400.00</h4><h3 class="del-price"><del>LKR 5,500.00</del></h3></div>
                    </div>
                    <div class="product-icon-wrapper"><div class="product-icon"><ul><li><a href="#"><span class="lnr lnr-sync"></span></a></li><li><a href="#"><span class="lnr lnr-heart"></span></a></li><li><a href="#"><span class="lnr lnr-cart"></span></a></li></ul></div></div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>
</div>

{{-- ═══ STATIC SLIDER ═══ --}}
<div class="static-slider-area dotted-style pt-50 pb-80 d-none d-md-block">
    <div class="static-slider-active">
        @foreach([1,2,3] as $i)
        <div class="static-single-slider">
            <div class="static-slider-img"><img src="{{ asset('assets/images/static/'.$i.'.jpg') }}" alt="" /></div>
            <div class="static-slider-text">
                <h2>Chairs &amp; Chaises</h2>
                <h1>Ethen Accent Chair - Laguna</h1>
                <p>Vacation at Home. With its dashingly refined good looks, the Ethen accent chair is perfectly suited for any room that can use a dose of vibrant colour.</p>
                <a href="{{ route('shop') }}" class="shopnow">shop now</a>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ═══ FEATURED + SALE PRODUCTS ═══ --}}
<div class="feature-preduct-area pb-50">
    <div class="container">
        <div class="row dotted-style3">
            {{-- Featured --}}
            <div class="col-md-6">
                <div class="section-title"><h2>featured products</h2><p>We offer the best selection furniture at prices you will love!</p></div>
                <div class="row-wrapper feature-preduct-active">
                    @forelse($featuredProducts as $product)
                    <div class="custom-col">
                        <div class="single-new-product">
                            <div class="product-img">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" class="first_img" alt="{{ $product->name }}"
    data-name="{{ $product->name }}"
    data-price="{{ number_format($product->price, 2) }}"
data-old-price="{{ $product->old_price ? number_format($product->old_price, 2) : '' }}"
    data-url="{{ route('product.show', $product->slug) }}" />
                                    @php $second = $allProducts->where('category_id', $product->category_id)->where('id', '!=', $product->id)->shuffle()->first(); if(!$second) $second = $allProducts->where('id', '!=', $product->id)->shuffle()->first(); @endphp
                                    <img src="{{ $second ? $second->image_url : $product->image_url }}" class="seceond_img" alt=""
    data-name="{{ $second ? $second->name : $product->name }}"
    data-price="{{ $second ? number_format($second->price, 2) : number_format($product->price, 2) }}"
data-old-price="{{ $second ? ($second->old_price ? number_format($second->old_price, 2) : '') : ($product->old_price ? number_format($product->old_price, 2) : '') }}"
    data-url="{{ $second ? route('product.show', $second->slug) : route('product.show', $product->slug) }}" />
                                </a>
                                <div class="new-product-action feature-action">
                                    <a href="#" class="home-checkout-btn" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                                    <a href="#" class="add-to-cart" data-id="{{ $product->id }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                                    <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                                </div>
                            </div>
                            <div class="product-content text-center">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover-product-url"><h3 class="hover-product-name">{{ $product->name }}</h3></a>
                                <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                <h4 class="hover-product-price">LKR {{ number_format($product->price, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    @empty
                    @for($i=1;$i<=2;$i++)
                    <div class="custom-col"><div class="single-new-product"><div class="product-img"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/product/'.$i.'.jpg') }}" class="first_img" alt="" /><img src="{{ asset('assets/images/product/'.($i+1).'.jpg') }}" class="seceond_img" alt="" /></a><div class="new-product-action feature-action"><a href="#"><span class="lnr lnr-sync"></span></a><a href="{{ route('shop') }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a><a href="#"><span class="lnr lnr-heart"></span></a></div></div><div class="product-content text-center"><a href="{{ route('shop') }}"><h3>Beaumont Summit</h3></a><div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div><h4>LKR 4,400.00</h4></div></div></div>
                    @endfor
                    @endforelse
                </div>
            </div>
            {{-- Sale --}}
            <div class="col-md-6">
                <div class="section-title"><h2>sale products</h2><p>Browse the collection of our on sale products.</p></div>
                <div class="row-wrapper feature-preduct-active">
                    @forelse($saleProducts as $product)
                    <div class="custom-col">
                        <div class="single-new-product">
                            <div class="product-img">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" class="first_img" alt="{{ $product->name }}"
    data-name="{{ $product->name }}"
    data-price="{{ number_format($product->price, 2) }}"
data-old-price="{{ $product->old_price ? number_format($product->old_price, 2) : '' }}"
    data-url="{{ route('product.show', $product->slug) }}" />
                                    @php $second = $allProducts->where('category_id', $product->category_id)->where('id', '!=', $product->id)->shuffle()->first(); if(!$second) $second = $allProducts->where('id', '!=', $product->id)->shuffle()->first(); @endphp
                                    <img src="{{ $second ? $second->image_url : $product->image_url }}" class="seceond_img" alt=""
    data-name="{{ $second ? $second->name : $product->name }}"
    data-price="{{ $second ? number_format($second->price, 2) : number_format($product->price, 2) }}"
data-old-price="{{ $second ? ($second->old_price ? number_format($second->old_price, 2) : '') : ($product->old_price ? number_format($product->old_price, 2) : '') }}"
    data-url="{{ $second ? route('product.show', $second->slug) : route('product.show', $product->slug) }}" />
                                </a>
                                <div class="new-product-action feature-action">
                                    <a href="#" class="home-checkout-btn" data-id="{{ $product->id }}"><span class="lnr lnr-sync"></span></a>
                                    <a href="#" class="add-to-cart" data-id="{{ $product->id }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a>
                                    <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><span class="lnr lnr-heart"></span></a>
                                </div>
                            </div>
                            <div class="product-content text-center">
                                <a href="{{ route('product.show', $product->slug) }}" class="hover-product-url"><h3 class="hover-product-name">{{ $product->name }}</h3></a>
                                <div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>
                                <h4 class="hover-product-price">LKR {{ number_format($product->price, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    @empty
                    @for($i=5;$i<=6;$i++)
                    <div class="custom-col"><div class="single-new-product"><div class="product-img"><a href="{{ route('shop') }}"><img src="{{ asset('assets/images/product/'.$i.'.jpg') }}" class="first_img" alt="" /><img src="{{ asset('assets/images/product/'.($i+1).'.jpg') }}" class="seceond_img" alt="" /></a><div class="new-product-action feature-action"><a href="#"><span class="lnr lnr-sync"></span></a><a href="{{ route('shop') }}"><span class="lnr lnr-cart cart_pad"></span>Add to Cart</a><a href="#"><span class="lnr lnr-heart"></span></a></div></div><div class="product-content text-center"><a href="{{ route('shop') }}"><h3>Beaumont Summit</h3></a><div class="product-price-star"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div><h4>LKR 4,400.00</h4></div></div></div>
                    @endfor
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ LATEST BLOGS ═══ --}}
<div class="blog-area pb-80 dotted-style3">
    <div class="container">
        <div class="section-title text-center">
            <h2>latest blogs</h2>
            <p>Do you want to present posts in the best way to highlight interesting <br /> moments of your blog? Focus on the latest new!</p>
        </div>
        <div class="row-wrapper blog-carousel-active">
            @forelse($latestBlogs as $blog)
            <div class="custom-col">
                <div class="single-blog">
                    <div class="blog-img">
                        <a href="{{ route('blog.index', $blog->slug) }}">
                            <img src="{{ $blog->image ? asset('storage/'.$blog->image) : asset('assets/images/blog/1.jpg') }}" alt="{{ $blog->title }}" />
                        </a>
                    </div>
                    <div class="blog-info">
                        <a href="{{ route('blog.show', $blog->slug) }}"><h2>{{ $blog->title }}</h2></a>
                        <p>{{ Str::limit(strip_tags($blog->content), 120) }}</p>
                        <a href="{{ route('blog.index') }}">Read more <span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
            </div>
            @empty
            @foreach(['1','2','3'] as $i)
            <div class="custom-col"><div class="single-blog"><div class="blog-img"><a href="{{ route('blog.index') }}"><img src="{{ asset('assets/images/blog/'.$i.'.jpg') }}" alt="" /></a></div><div class="blog-info"><a href="{{ route('blog.index') }}"><h2>Fashion Meets Furniture</h2></a><p>The next vignette featured two colorful outfits inspired by Hans Christian Andersen character Gerda.</p><a href="{{ route('blog.index') }}">Read more <span class="lnr lnr-arrow-right"></span></a></div></div></div>
            @endforeach
            @endforelse
        </div>
    </div>
</div>

{{-- ═══ HOW IT WORKS ═══ --}}
<div class="purchase-progress-area pb-80">
    <div class="container">
        <div class="section-title text-center"><h2>Buying furniture on GRAND furniture is simple!</h2></div>
        <div class="row">
            @foreach([1,2,3] as $i)
            <div class="{{ $i == 2 ? 'col-lg-4 d-none d-lg-block' : 'col-lg-4 col-md-6' }}">
                <div class="single-purchase text-center">
                    <div class="purchase-img"><img src="{{ asset('assets/images/purchase/'.$i.'.jpg') }}" alt="" /></div>
                    <div class="purchase-info"><h3>Discover Great Furniture</h3><p>Browse listings of gently used furniture finds by category or brand.</p></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══ NEWSLETTER ═══ --}}
<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div>
            <div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address"/><button type="submit"><span class="lnr lnr-envelope"></span></button></form></div></div>
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).on('mouseenter', '.single-new-product', function() {
    var $card     = $(this);
    var $second   = $card.find('.seceond_img');
    var secName   = $second.data('name');
    var secPrice  = $second.data('price');
    var secOld    = $second.data('old-price');
    var secUrl    = $second.data('url');

    if (secName && secName !== $card.find('.hover-product-name').text()) {
        $card.find('.hover-product-name').text(secName);
        $card.find('.hover-product-price').text('LKR ' + secPrice);
        $card.find('.hover-product-url').attr('href', secUrl);

        if (secOld) {
            $card.find('.hover-product-old-price').html('<del>LKR ' + secOld + '</del>').show();
        } else {
            $card.find('.hover-product-old-price').hide();
        }
    }
});

$(document).on('mouseleave', '.single-new-product', function() {
    var $card      = $(this);
    var $first     = $card.find('.first_img');
    var origName   = $first.data('name');
    var origPrice  = $first.data('price');
    var origOld    = $first.data('old-price');
    var origUrl    = $first.data('url');

    if (origName) {
        $card.find('.hover-product-name').text(origName);
        $card.find('.hover-product-price').text('LKR ' + origPrice);
        $card.find('.hover-product-url').attr('href', origUrl);

        if (origOld) {
            $card.find('.hover-product-old-price').html('<del>LKR ' + origOld + '</del>').show();
        } else {
            $card.find('.hover-product-old-price').hide();
        }
    }
});
</script>
@endpush


@endsection