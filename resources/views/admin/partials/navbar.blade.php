<!-- admin mainmenu-area-start -->
<div class="mainmenu-area bg-color-1" id="main_h">
    <div class="container">
        <div class="row">
            <div class="col-md-9 d-none d-md-block">
                <div class="mainmenu d-none d-md-block">
                    <nav>
                        <ul>
                            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}">Admin Home</a>
                            </li>

                            <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.index') }}">Products</a>
                                <ul>
                                    <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                                    <li><a href="{{ route('admin.products.index') }}">Edit / Delete Product</a></li>
                                </ul>
                            </li>

                            <li class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.blog.index') }}">Blog</a>
                                <ul>
                                    <li><a href="{{ route('admin.blog.create') }}">Create Blog</a></li>
                                    <li><a href="{{ route('admin.blog.index') }}">Edit / Delete Blog</a></li>
                                </ul>
                            </li>

                            <li class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.contacts.index') }}">Contact Submissions</a>
                            </li>

                            <li>
                                <a href="{{ url('/') }}" target="_blank">View Store</a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="col-md-3">
                <div class="menu-search-box">
                    <form action="{{ route('admin.products.index') }}" method="GET">
                        <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}" />
                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Menu --}}
<div class="mobile-menu-area d-flex d-md-none">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="mobile-menu">
                    <nav id="mobile-menu">
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Admin Home</a></li>
                            <li><a href="{{ route('admin.products.index') }}">Products</a>
                                <ul>
                                    <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                                    <li><a href="{{ route('admin.products.index') }}">Edit/Delete</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('admin.blog.index') }}">Blog</a>
                                <ul>
                                    <li><a href="{{ route('admin.blog.create') }}">Create Blog</a></li>
                                    <li><a href="{{ route('admin.blog.index') }}">Edit/Delete</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('admin.contacts.index') }}">Contact Submissions</a></li>
                            <li><a href="{{ url('/') }}">View Store</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
<!-- admin mainmenu-area-end -->
