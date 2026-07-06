<header>
    <div class="header-top-area ptb-10">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-md-8">

                    {{-- My Account — Grand exact structure --}}
                    <div class="header-top-left">
                        <ul>
                            @auth
                                @php
                                    $isAdminUser = strtolower(trim((string) auth()->user()->email)) === strtolower(trim((string) env('ADMIN_EMAIL', 'admin@gmail.com')));
                                @endphp

                                @if($isAdminUser)
                                    <li class="click_menu">
                                        <a href="#">My Account <i class="fa fa-angle-down"></i></a>
                                        <ul class="click_menu_show">
                                            <li><a href="{{ url('/admin') }}">Admin Dashboard</a></li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();document.getElementById('hlogout').submit();">Sign Out</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('register') }}">Create an Account</a></li>
                                    <form id="hlogout" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                                @else
                                    <li class="click_menu">
                                        <a href="#">{{ auth()->user()->name }} <i class="fa fa-angle-down"></i></a>
                                            <ul class="click_menu_show">
                                                <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                                                <li><a href="{{ route('reviews.index') }}">My Reviews</a></li>
                                                <li><a href="{{ route('wishlist.index') }}">My Wish List</a></li>
                                                <li><a href="{{ route('cart.index') }}">My Cart</a></li>
                                                <li>
                                                <a href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();document.getElementById('hlogout').submit();">Sign Out</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <form id="hlogout" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                                @endif
                            @else
                                <li class="click_menu">
                                    <a href="#">My Account <i class="fa fa-angle-down"></i></a>
                                    <ul class="click_menu_show">
                                        <li><a href="{{ route('login') }}">Compare Products</a></li>
                                        <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                                        <li><a href="{{ route('login') }}">My Account</a></li>
                                        <li><a href="{{ route('wishlist.index') }}">My Wish List</a></li>
                                        <li><a href="{{ route('reviews.index') }}">My Reviews</a></li>
                                        <li><a href="{{ route('login') }}">Sign In</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('register') }}">Create an Account</a></li>
                            @endauth
                        </ul>
                    </div>

                </div>

                <div class="col-xl-3 col-md-4">
                    <div class="header-top-right">
                        <ul>
                            <li>
                                <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}">
                                    <span class="lnr lnr-heart"></span> wish list
                                    ({{ $wishlistCount }})
                                </a>
                            </li>
                            <li>
                                <a href="{{ auth()->check() ? route('checkout.index') : route('login') }}">
                                    <span class="lnr lnr-sync"></span> checkout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom-area ptb-50">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-2 col-md-3">
                    <div class="logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="Grand" /></a>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 d-none d-lg-block">
                    <div class="single-header-bottom-info">
                        <div class="header-bottom-icon"><span class="lnr lnr-rocket"></span></div>
                        <div class="header-bottom-text"><h3>FREE SHIPPING</h3><p>Free shipping on all order</p></div>
                    </div>
                    <div class="single-header-bottom-info">
                        <div class="header-bottom-icon"><span class="lnr lnr-phone"></span></div>
                        <div class="header-bottom-text"><h3>SUPPORT 24/7</h3><p>We support online 24 hours a day</p></div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-9">
                    <div class="header-bottom-right">
                        <div class="shop-cart">
                            <a href="{{ route('cart.index') }}">
                                <span class="lnr lnr-cart"></span>My Cart - items(<span class="cart-count">{{ auth()->check() ? $hCart->sum('quantity') : 0 }}</span>)
                            </a>
                        </div>
                        @auth
                        
                        <div class="shop-cart-hover fix">
                            <ul>
                                @forelse($hCart as $item)
                                <li>
                                    <div class="cart-img">
                                        <a href="{{ route('product.show',$item->product->slug) }}">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" />
                                        </a>
                                    </div>
                                    <div class="cart-content">
                                        <h4><a href="#">{{ $item->quantity }} x {{ Str::limit($item->product->name,10) }}</a></h4>
                                        <span class="cart-price">LKR {{ number_format($item->product->price,2) }}</span>
                                    </div>
                                    <div class="cart-del">
                                        <form action="{{ route('cart.remove',$item->id) }}" method="POST" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                                @empty
                                <li style="padding:12px;text-align:center;"><small>Cart is empty</small></li>
                                @endforelse
                                @if($hCart->count() > 0)
                                    <li class="total-price"><span>Total LKR {{ number_format($hTotal,2) }}</span></li>
                                @endif
                                <li class="checkout-bg">
                                    <a href="{{ route('checkout.index') }}">checkout <i class="fa fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>