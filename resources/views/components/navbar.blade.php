<div class="mainmenu-area bg-color-1" id="main_h">
    <div class="container">
        <div class="row">
            <div class="col-md-9 d-none d-md-block">
                <div class="mainmenu d-none d-md-block">
                    <nav>
                        <ul>
                            <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a>
                                <!-- <ul>
                                    <li><a href="{{ route('home') }}">home version 1</a></li>
                                    <li><a href="{{ route('home') }}">home version 2</a></li>
                                    <li><a href="{{ route('home') }}">home version 3</a></li>
                                    <li><a href="{{ route('home') }}">home version 4</a></li>
                                </ul> -->
                            </li>
                            <li class="{{ request()->is('shop*') && request('category')=='bedroom' ? 'active' : '' }}"><a href="{{ route('shop',['category'=>'bedroom']) }}">Bedroom</a>
                                <div class="megamenu">
                                    <span><a href="#" class="megatitle">Beds</a><a href="#">Platform Beds</a><a href="#">Storage Beds</a><a href="#">Regular Beds</a><a href="#">Sleigh Beds</a><a href="#">modern beds</a></span>
                                    <span><a href="#" class="megatitle">Nightstands</a><a href="#">Brown Finish</a><a href="#">Distressed</a><a href="#">Cherry Finish</a><a href="#">Weathered</a><a href="#">Laundry</a></span>
                                    <span><a href="#" class="megatitle">Headboards</a><a href="#">Upholstered</a><a href="#">Tufted</a><a href="#">Storage</a><a href="#">padded</a><a href="#">Outdoor</a></span>
                                </div>
                            </li>
                            <li class="{{ request()->is('shop*') && request('category')=='living-room' ? 'active' : '' }}"><a href="{{ route('shop',['category'=>'living-room']) }}">Living Room</a>
                                <div class="megamenu megamenu2 living-megamenu">
                                    <span><a href="#" class="megatitle">Living Chairs</a><a href="#">mattress</a><a href="#">bunk bed</a><a href="#">Weathered</a><a href="#">sideboard</a><a href="#">Dresses</a></span>
                                    <span><a href="#" class="megatitle">Bootees Bags</a><a href="#">Brown Finish</a><a href="#">Distressed</a><a href="#">Tufted</a><a href="#">Cherry Finish</a><a href="#">Weathered</a></span>
                                    <span><a href="#" class="megatitle">Headboards</a><a href="#">Upholstered</a><a href="#">Tufted</a><a href="#">Storage</a><a href="#">Sweaters</a><a href="#">padded</a></span>
                                    <span><a href="#" class="megatitle">Headboards</a><a href="#">Upholstered</a><a href="#">Tufted</a><a href="#">Storage</a><a href="#">Wedges</a><a href="#">padded</a></span>
                                </div>
                            </li>
                            <li class="{{ request()->is('shop*') && request('category')=='dining-room' ? 'active' : '' }}"><a href="{{ route('shop',['category'=>'dining-room']) }}">Dining Room</a>
                                <div class="megamenu dining-megamenu">
                                    <span><a href="#" class="megatitle">Dining tables</a><a href="#">Crochet</a><a href="#">Sleeveless</a><a href="#">Stripes</a><a href="#">Sweaters</a></span>
                                    <span><a href="#" class="megatitle">Dining chairs</a><a href="#">Dining chairs</a><a href="#">Ankle</a><a href="#">Cherry Finish</a><a href="#">Weathered</a></span>
                                    <span><a href="#" class="megatitle">Dining sets</a><a href="#">Upholstered</a><a href="#">Tufted</a><a href="#">Footwear</a><a href="#">Wedges</a></span>
                                </div>
                            </li>
                            <li><a href="#">pages</a>
                                <ul>
                                    <li><a href="{{ route('wishlist.index') }}">my wishlist</a></li>
                                    <li><a href="{{ route('cart.index') }}">cart page</a></li>
                                    <li><a href="{{ route('blog.index') }}">blog page</a></li>
                                    <li><a href="{{ route('checkout.index') }}">checkout</a></li>
                                    <li><a href="{{ route('shop') }}">shop page</a></li>
                                    <li><a href="{{ route('contact') }}">contact us</a></li>
                                    @guest
                                    <li><a href="{{ route('register') }}">customer-account</a></li>
                                    <li><a href="{{ route('login') }}">customer-login</a></li>
                                    @endguest
                                </ul>
                            </li>
                            <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">contact</a></li>
                            <li class="{{ request()->routeIs('blog*') ? 'active' : '' }}"><a href="{{ route('blog.index') }}">blog</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-md-3">
                <div class="menu-search-box">
                    <form action="{{ route('shop') }}" method="GET">
                        <input type="text" name="q" placeholder="Search here..." value="{{ request('q') }}"/>
                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mobile-menu-area d-flex d-md-none">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="mobile-menu">
                    <nav id="mobile-menu">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('shop',['category'=>'bedroom']) }}">Bedroom</a>
                                <ul><li><a href="#">Beds</a><ul><li><a href="#">Platform Beds</a></li><li><a href="#">Storage Beds</a></li><li><a href="#">Regular Beds</a></li><li><a href="#">Sleigh Beds</a></li><li><a href="#">modern beds</a></li></ul></li><li><a href="#">Nightstands</a><ul><li><a href="#">Brown Finish</a></li><li><a href="#">Distressed</a></li><li><a href="#">Cherry Finish</a></li><li><a href="#">Weathered</a></li><li><a href="#">Laundry</a></li></ul></li><li><a href="#">Headboards</a><ul><li><a href="#">Upholstered</a></li><li><a href="#">Tufted</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li></ul></li></ul>
                            </li>
                            <li><a href="{{ route('shop',['category'=>'living-room']) }}">Living Room</a>
                                <ul><li><a href="#">Beds</a><ul><li><a href="#">Platform Beds</a></li><li><a href="#">Storage Beds</a></li><li><a href="#">Regular Beds</a></li><li><a href="#">Sleigh Beds</a></li><li><a href="#">modern beds</a></li></ul></li><li><a href="#">Nightstands</a><ul><li><a href="#">Brown Finish</a></li><li><a href="#">Distressed</a></li><li><a href="#">Cherry Finish</a></li><li><a href="#">Weathered</a></li><li><a href="#">Laundry</a></li></ul></li><li><a href="#">Headboards</a><ul><li><a href="#">Upholstered</a></li><li><a href="#">Tufted</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li></ul></li><li><a href="#">Headboards</a><ul><li><a href="#">Upholstered</a></li><li><a href="#">Tufted</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li><li><a href="#">Platform Beds</a></li></ul></li></ul>
                            </li>
                            <li><a href="{{ route('shop',['category'=>'dining-room']) }}">Dining Room</a>
                                <ul><li><a href="#">Dining tables</a><ul><li><a href="#">Crochet</a></li><li><a href="#">Sleeveless</a></li><li><a href="#">Regular Beds</a></li><li><a href="#">Stripes</a></li><li><a href="#">Sweaters</a></li></ul></li><li><a href="#">Dining chairs</a><ul><li><a href="#">Ankle</a></li><li><a href="#">Distressed</a></li><li><a href="#">Cherry Finish</a></li><li><a href="#">Weathered</a></li><li><a href="#">Laundry</a></li></ul></li><li><a href="#">Headboards</a><ul><li><a href="#">Upholstered</a></li><li><a href="#">Tufted</a></li><li><a href="#">Phery Finiss</a></li><li><a href="#">Platform Beds</a></li></ul></li></ul>
                            </li>
                            <li><a href="{{ route('shop') }}">pages</a>
                                <ul>
                                    <li><a href="{{ route('wishlist.index') }}">my wishlist</a></li>
                                    <li><a href="{{ route('cart.index') }}">cart page</a></li>
                                    <li><a href="{{ route('blog.index') }}">blog page</a></li>
                                    <li><a href="{{ route('checkout.index') }}">checkout</a></li>
                                    <li><a href="{{ route('shop') }}">shop page</a></li>
                                    <li><a href="{{ route('contact') }}">contact us</a></li>
                                    @guest
                                    <li><a href="{{ route('register') }}">customer-account</a></li>
                                    <li><a href="{{ route('login') }}">customer-login</a></li>
                                    @endguest
                                </ul>
                            </li>
                            <li><a href="{{ route('contact') }}">contact</a></li>
                            <li><a href="{{ route('blog.index') }}">blog</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
