<aside class="app-sidebar sticky" id="sidebar">

    {{-- Logo --}}
    <div class="app-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="desktop-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Grand Furniture" style="height:38px;">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="desktop-dark">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Grand Furniture" style="height:38px;">
        </a>
        <button type="button" class="sidebar-mobile-close d-lg-none"
                onclick="document.querySelector('.app-sidebar').classList.remove('close_sidebar');
                         document.querySelector('.app-offcanvas-overlay').classList.remove('overlay-open');"
                style="position:absolute;top:12px;right:12px;background:none;border:none;
                       font-size:22px;line-height:1;color:inherit;cursor:pointer;z-index:5;">
            <i class="ri-close-line"></i>
        </button>
    </div>

    <div class="app-sidebar-wrapper" id="sidebar-scroll">
        <nav class="app-sidebar-menu-wrapper nav flex-column sub-open">
            <div class="sidebar-left" id="sidebar-left"></div>

            <ul class="app-sidebar-main-menu">

                {{-- ══ MAIN ══════════════════════════════════ --}}
                <li class="sidebar-menu-category"><span class="category-name">Main</span></li>

                {{-- Dashboard --}}
                <li class="slide {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 21C13.4477 21 13 20.5523 13 20V12C13 11.4477 13.4477 11 14 11H20C20.5523 11 21 11.4477 21 12V20C21 20.5523 20.5523 21 20 21H14ZM4 13C3.44772 13 3 12.5523 3 12V4C3 3.44772 3.44772 3 4 3H10C10.5523 3 11 3.44772 11 4V12C11 12.5523 10.5523 13 10 13H4ZM9 11V5H5V11H9ZM4 21C3.44772 21 3 20.5523 3 20V16C3 15.4477 3.44772 15 4 15H10C10.5523 15 11 15.4477 11 16V20C11 20.5523 10.5523 21 10 21H4ZM5 19H9V17H5V19ZM15 19H19V13H15V19ZM13 4C13 3.44772 13.4477 3 14 3H20C20.5523 3 21 3.44772 21 4V8C21 8.55228 20.5523 9 20 9H14C13.4477 9 13 8.55228 13 8V4ZM15 5V7H19V5H15Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Dashboard</span>
                    </a>
                </li>

                <li class="slide {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupons.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon"><i class="ri-coupon-line"></i></div>
                        <span class="sidebar-menu-label">Coupons</span>
                    </a>
                </li>

                <li class="slide {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon"><i class="ri-list-check-2"></i></div>
                        <span class="sidebar-menu-label">Categories</span>
                    </a>
                </li>

                <li class="slide {{ request()->routeIs('admin.colors*') ? 'active' : '' }}">
                    <a href="{{ route('admin.colors.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon"><i class="ri-palette-line"></i></div>
                        <span class="sidebar-menu-label">Colors</span>
                    </a>
                </li>

                {{-- ══ ECOMMERCE ══════════════════════════════ --}}
                <li class="sidebar-menu-category"><span class="category-name">Ecommerce</span></li>

                {{-- Products --}}
                <li class="slide has-sub {{ request()->routeIs('admin.products*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="sidebar-menu-item">
                        <i class="ri-arrow-down-s-fill side-menu-angle"></i>
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21 11.6458V21C21 21.5523 20.5523 22 20 22H4C3.44772 22 3 21.5523 3 21V11.6458C2.37764 10.9407 2 10.0144 2 9V3C2 2.44772 2.44772 2 3 2H21C21.5523 2 22 2.44772 22 3V9C22 10.0144 21.6224 10.9407 21 11.6458ZM19 12.874C18.6804 12.9562 18.3453 13 18 13C16.8053 13 15.7329 12.4762 15 11.6458C14.2671 12.4762 13.1947 13 12 13C10.8053 13 9.73294 12.4762 9 11.6458C8.26706 12.4762 7.19469 13 6 13C5.6547 13 5.31962 12.9562 5 12.874V20H19V12.874ZM14 9C14 8.44772 14.4477 8 15 8C15.5523 8 16 8.44772 16 9C16 10.1046 16.8954 11 18 11C19.1046 11 20 10.1046 20 9V4H4V9C4 10.1046 4.89543 11 6 11C7.10457 11 8 10.1046 8 9C8 8.44772 8.44772 8 9 8C9.55228 8 10 8.44772 10 9C10 10.1046 10.8954 11 12 11C13.1046 11 14 10.1046 14 9Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Products</span>
                    </a>
                    <ul class="sidebar-menu child1">
                        <li class="slide {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                            <a class="sidebar-menu-item" href="{{ route('admin.products.index') }}">Products List</a>
                        </li>
                        <li class="slide {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                            <a class="sidebar-menu-item" href="{{ route('admin.products.create') }}">Add Product</a>
                        </li>
                    </ul>
                </li>

                {{-- Orders --}}
                <li class="slide has-sub {{ request()->routeIs('admin.orders*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="sidebar-menu-item">
                        <i class="ri-arrow-down-s-fill side-menu-angle"></i>
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7.00488 7.99966V5.99966C7.00488 3.23824 9.24346 0.999664 12.0049 0.999664C14.7663 0.999664 17.0049 3.23824 17.0049 5.99966V7.99966H20.0049C20.5572 7.99966 21.0049 8.44738 21.0049 8.99966V20.9997C21.0049 21.5519 20.5572 21.9997 20.0049 21.9997H4.00488C3.4526 21.9997 3.00488 21.5519 3.00488 20.9997V8.99966C3.00488 8.44738 3.4526 7.99966 4.00488 7.99966H7.00488ZM7.00488 9.99966H5.00488V19.9997H19.0049V9.99966H17.0049V11.9997H15.0049V9.99966H9.00488V11.9997H7.00488V9.99966ZM9.00488 7.99966H15.0049V5.99966C15.0049 4.34281 13.6617 2.99966 12.0049 2.99966C10.348 2.99966 9.00488 4.34281 9.00488 5.99966V7.99966Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Orders</span>
                    </a>
                    <ul class="sidebar-menu child1">
                        <li class="slide {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                            <a class="sidebar-menu-item" href="{{ route('admin.orders.index') }}">Order List</a>
                        </li>
                    </ul>
                </li>

                {{-- Customers --}}
                <li class="slide {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <a href="{{ url('/admin-panel/customers') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 7H24V9H19V7ZM17 12H24V14H17V12ZM20 17H24V19H20V17ZM2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13ZM10 11C12.21 11 14 9.21 14 7C14 4.79 12.21 3 10 3C7.79 3 6 4.79 6 7C6 9.21 7.79 11 10 11Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Customers</span>
                    </a>
                </li>

                {{-- ══ CONTENT ═════════════════════════════ --}}

                <li class="slide {{ request()->routeIs('admin-reviews*') ? 'active' : '' }}">
                    <a href="{{ route('admin.admin-reviews.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon"><i class="ri-star-line"></i></div>
                        <span class="sidebar-menu-label">Reviews</span>
                    </a>
                </li>

                <li class="sidebar-menu-category"><span class="category-name">Content</span></li>

                {{-- Blog --}}
                <li class="slide has-sub {{ request()->is('admin-panel/blog*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="sidebar-menu-item">
                        <i class="ri-arrow-down-s-fill side-menu-angle"></i>
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Blog</span>
                    </a>
                    <ul class="sidebar-menu child1">
                        <li class="slide {{ request()->is('admin-panel/blog') ? 'active' : '' }}">
                            <a class="sidebar-menu-item" href="{{ url('/admin-panel/blog') }}">Blog Posts</a>
                        </li>
                        <li class="slide {{ request()->is('admin-panel/blog/create') ? 'active' : '' }}">
                            <a class="sidebar-menu-item" href="{{ url('/admin-panel/blog/create') }}">Add Post</a>
                        </li>
                    </ul>
                </li>

                {{-- ══ OTHER ════════════════════════════════ --}}
                <li class="sidebar-menu-category"><span class="category-name">Other</span></li>

                <li class="slide {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon"><i class="ri-bar-chart-2-line"></i></div>
                        <span class="sidebar-menu-label">Sales Reports</span>
                    </a>
                </li>

                {{-- Contacts --}}
                <li class="slide {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}" class="sidebar-menu-item">
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22 20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V20ZM20 19V5H4V19H20ZM7 7H17V9H7V7ZM7 11H17V13H7V11ZM7 15H14V17H7V15Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Contacts</span>
                    </a>
                </li>

                {{-- ══ ACCOUNT ══════════════════════════════ --}}
                <li class="sidebar-menu-category"><span class="category-name">Account</span></li>

                {{-- View Store --}}
                <li class="slide">
                    <a href="{{ route('home') }}" target="_blank" class="sidebar-menu-item">
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21 13.2422V20H22V22H2V20H3V13.2422C1.79401 12.435 1 11.0602 1 9.5C1 8.67286 1.22443 7.87621 1.63322 7.19746L4.3453 2.5C4.52393 2.1906 4.85406 2 5.21132 2H18.7887C19.1459 2 19.4761 2.1906 19.6547 2.5L22.3575 7.18172C22.7756 7.87621 23 8.67286 23 9.5C23 11.0602 22.206 12.435 21 13.2422ZM19 13.9725C18.8358 13.9907 18.669 14 18.5 14C17.2956 14 16.2014 13.4878 15.4253 12.6588L14.0002 14.0839C13.0916 14.8124 11.9092 15.25 10.6253 15.25C9.34136 15.25 8.15895 14.8124 7.25027 14.0839L5.57478 12.6588C4.79863 13.4878 3.70443 14 2.5 14C2.33103 14 2.1642 13.9907 2 13.9725V20H19V13.9725ZM5.78865 4L3.35598 8.21321C3.12511 8.59843 3 9.04 3 9.5C3 10.8807 4.11929 12 5.5 12C6.53253 12 7.43346 11.4027 7.87205 10.5H7.5C7.22386 10.5 7 10.2761 7 10V9.5L9.5 5.5L11.5 8L13 6L15 9V10C15 10.2761 14.7761 10.5 14.5 10.5H14.1279C14.5665 11.4027 15.4675 12 16.5 12C17.8807 12 19 10.8807 19 9.5C19 9.04 18.8749 8.59843 18.644 8.21321L16.2113 4H5.78865Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">View Store</span>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="slide">
                    <a href="javascript:void(0);"
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                       class="sidebar-menu-item">
                        <div class="side-menu-icon">
                            <i class="">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 22C4.44772 22 4 21.5523 4 21V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V6H18V4H6V20H18V18H20V21C20 21.5523 19.5523 22 19 22H5ZM18 16V13H11V11H18V8L23 12L18 16Z"/>
                                </svg>
                            </i>
                        </div>
                        <span class="sidebar-menu-label">Logout</span>
                    </a>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}"
                          method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
    </div>

</aside>