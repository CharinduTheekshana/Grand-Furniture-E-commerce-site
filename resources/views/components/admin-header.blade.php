<div class="app-header-area">
    <header class="app-header" id="header">
        <div class="app-header-inner">

            <div class="app-header-left">
                <div class="app-header-element">
                    <button class="app-header-hamburger" id="sidebar-toggle">
                        <i class="ri-menu-line"></i>
                    </button>
                </div>
                <div class="app-header-mobile-logo d-lg-none">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Grand" style="height:30px;">
                    </a>
                </div>
                <div class="app-header-search d-none d-lg-block">
                    <div class="search-box">
                        <i class="ri-search-line search-icon"></i>
                        <input type="search" class="form-control" placeholder="Search products, orders...">
                    </div>
                </div>
            </div>

            <div class="app-header-right">

                <div class="app-header-fullscreen app-header-circle cursor-pointer" id="fullscreen-btn">
                    <i class="ri-fullscreen-line"></i>
                </div>

                <div class="app-header-element dropdown">
                    <button class="app-header-circle" data-bs-toggle="dropdown">
                        <i class="ri-notification-3-line"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width:300px;">
                        <div class="p-3 border-bottom"><h6 class="mb-0">Notifications</h6></div>
                        <div class="p-3 text-center text-muted"><small>No new notifications</small></div>
                    </div>
                </div>

                <div class="app-header-element dropdown">
                    <button class="d-flex align-items-center gap-2 border-0 bg-transparent"
                            data-bs-toggle="dropdown">
                        <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle"
                             style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                            <i class="ri-user-line"></i>
                        </div>
                        <div class="d-none d-md-block text-start">
                            <span class="d-block fs-13 fw-medium lh-1">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <span class="d-block fs-11 text-muted">Administrator</span>
                        </div>
                        <i class="ri-arrow-down-s-line text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                <i class="ri-store-2-line me-2"></i> View Store
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                               onclick="document.getElementById('admin-logout-form').submit()">
                                <i class="ri-logout-box-r-line me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>
</div>