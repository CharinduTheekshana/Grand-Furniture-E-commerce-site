<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Grand Furniture</title>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/waves.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/nano.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/plugins/swiper.min.css') }}">

    <style>
        .rt-toast {
            position: fixed; bottom: 20px; right: 20px; z-index: 99999;
            background: #333; color: #fff; padding: 12px 20px;
            border-radius: 4px; font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.3); display: none;
        }
        .rt-toast.success { background: #2ecc71; }
        .rt-toast.info    { background: #3498db; }
        .rt-toast.warning { background: #f39c12; }
        .rt-toast.error   { background: #e74c3c; }
    </style>

    @stack('styles')
</head>

<body class="body-area">

    <div class="page">
        @include('components.admin-sidebar')
        @include('components.admin-header')

        <div class="app-content-area">
            <div class="app-content-wrap">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    {{-- page end --}}

    {{-- Back to top --}}
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    {{-- Theme Switcher --}}
    <div class="theme-switcher-settings d-none">
        <div class="theme-switcher">
            <i class="ri-sun-line change-theme" id="theme-button"></i>
        </div>
    </div>

    <div class="bd-theme-settings-area transition-3">
        <div class="bd-theme-wrapper">
            <div class="bd-theme-header text-center">
                <h4 class="bd-theme-header-title">Template Settings</h4>
            </div>
            <div class="theme-switcher-radio d-flex align-items-center gap-25 mb-25">
                <div class="form-check switch-select">
                    <input class="form-check-input" type="radio" name="theme-style"
                           id="switcher-light-theme" checked>
                    <label class="form-check-label" for="switcher-light-theme">Light</label>
                </div>
                <div class="form-check switch-select">
                    <input class="form-check-input" type="radio" name="theme-style"
                           id="switcher-dark-theme">
                    <label class="form-check-label" for="switcher-dark-theme">Dark</label>
                </div>
            </div>
            <div class="direction-switcher d-flex align-items-center gap-25">
                <div class="form-check switch-select">
                    <input class="form-check-input" type="radio" name="direction"
                           id="switcher-ltr" value="ltr" checked>
                    <label class="form-check-label" for="switcher-ltr">LTR</label>
                </div>
                <div class="form-check switch-select">
                    <input class="form-check-input" type="radio" name="direction"
                           id="switcher-rtl" value="rtl">
                    <label class="form-check-label" for="switcher-rtl">RTL</label>
                </div>
            </div>
            <div class="bd-theme-settings">
                <div class="bd-theme-settings-wrapper">
                    <div class="bd-theme-settings-open">
                        <button class="bd-theme-settings-open-btn">
                            <span class="bd-theme-settings-gear">
                                <i class="ri-settings-3-line"></i>
                            </span>
                            <span class="bd-theme-settings-close">
                                <i class="ri-close-line"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Theme Switcher end --}}

    {{-- Toast --}}
    <div class="rt-toast" id="rt-toast"></div>

    {{-- Flash messages --}}
    @if(session('success'))
        <script>window._flashMsg = { msg: "{{ session('success') }}", type: 'success' };</script>
    @elseif(session('error'))
        <script>window._flashMsg = { msg: "{{ session('error') }}", type: 'error' };</script>
    @elseif(session('warning'))
        <script>window._flashMsg = { msg: "{{ session('warning') }}", type: 'warning' };</script>
    @endif

    {{-- JS — order matters! --}}
    <script src="{{ asset('assets/admin/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/simplebar-active.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/height-equal.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/backtotop.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/sidebar.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/swiper.min.js') }}"></script>

    {{-- Pusher realtime --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function showToast(msg, type) {
            $('#rt-toast').removeClass('success info warning error')
                .addClass(type || 'info').text(msg).fadeIn(300);
            setTimeout(function(){ $('#rt-toast').fadeOut(400); }, 4000);
        }

        if (typeof window._flashMsg !== 'undefined') {
            $(document).ready(function(){
                showToast(window._flashMsg.msg, window._flashMsg.type);
            });
        }

        try {
            var pusher = new Pusher('{{ env("PUSHER_APP_KEY", "local") }}', {
                cluster:           '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
                wsHost:            '{{ env("PUSHER_HOST", "127.0.0.1") }}',
                wsPort:            {{ env("PUSHER_PORT", 6001) }},
                wssPort:           {{ env("PUSHER_PORT", 6001) }},
                forceTLS:          false,
                disableStats:      true,
                enabledTransports: ['ws', 'wss'],
            });

            var adminCh = pusher.subscribe('admin-dashboard');

            adminCh.bind('order.placed', function(data) {
                showToast('🛒 New Order #' + data.id + ' — LKR ' + parseFloat(data.total).toLocaleString(), 'success');
                var $badge = $('#admin-order-count');
                if ($badge.length) {
                    $badge.text(parseInt($badge.text() || 0) + 1);
                }
            });

        } catch(e) {}
    </script>

    @stack('scripts')
</body>
</html>