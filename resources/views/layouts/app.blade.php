<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Grand | Home')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nivo-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons-icon-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    @stack('styles')
</head>
<body>
    <x-header />
    <x-navbar />
    @yield('content')
    <x-footer />

    {{-- Toast --}}
    <div class="rt-toast" id="rt-toast"></div>

    {{-- Session Flash --}}
    @if(session('success'))
    <script>window._flashMsg = { msg: "{{ session('success') }}", type: 'success' };</script>
    @elseif(session('error'))
    <script>window._flashMsg = { msg: "{{ session('error') }}", type: 'error' };</script>
    @elseif(session('warning'))
    <script>window._flashMsg = { msg: "{{ session('warning') }}", type: 'warning' };</script>
    @elseif(session('info'))
    <script>window._flashMsg = { msg: "{{ session('info') }}", type: 'info' };</script>
    @endif

    <script src="{{ asset('assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.meanmenu.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nivo.slider.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrolly.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        var toastIcons = {
            success: 'check-circle', error: 'times-circle',
            warning: 'exclamation-circle', info: 'info-circle'
        };

        function showToast(msg, type) {
            type = type || 'success';
            $('#rt-toast')
                .removeClass('success error warning info')
                .addClass(type)
                .html('<i class="fa fa-' + (toastIcons[type]||'check-circle') + '"></i> ' + msg)
                .stop(true).fadeIn(300);
            setTimeout(function(){ $('#rt-toast').fadeOut(500); }, 4000);
        }

        $(document).ready(function() {
            if (window._flashMsg) {
                showToast(window._flashMsg.msg, window._flashMsg.type);
            }
        });

        function saveIntendedAndLogin(action, productId, qty) {
            $.post('/save-intended', { action: action, product_id: productId, qty: qty || 1 },
                function() { window.location.href = '{{ route("login") }}'; })
            .fail(function() { window.location.href = '{{ route("login") }}'; });
        }

        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var qty = $('#qty').val() || 1;
            @auth
            $.post('/cart/add/' + productId, { qty: qty }, function(res) {
                if (res.redirect) { window.location.href = res.redirect; }
                else { $('.cart-count').text(res.count); showToast('Added to cart!', 'success'); }
            });
            @else
            saveIntendedAndLogin('cart', productId, qty);
            @endauth
        });

        $(document).on('click', '.wishlist-btn', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var $btn = $(this);
            @auth
            $.post('/wishlist/' + productId, {}, function(res) {
                if (res.status === 'added') { $btn.addClass('active'); showToast('Added to wishlist!', 'success'); }
                else { $btn.removeClass('active'); showToast('Removed from wishlist!', 'error'); }
            });
            @else
            saveIntendedAndLogin('wishlist', productId);
            @endauth
        });

        $(document).on('click', '.checkout-guest-link', function(e) {
            e.preventDefault();
            var productId = $(this).data('id') || 0;
            var qty = parseInt($('#qty').val()) || 1;
            @auth
            $.post('/cart/add/' + productId, { qty: qty }, function() {
                window.location.href = '{{ route("checkout.index") }}';
            });
            @else
            saveIntendedAndLogin('checkout', productId, qty);
            @endauth
        });

        $(document).on('click', '.home-checkout-btn', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            @auth
            $.post('/cart/add/' + productId, { qty: 1 }, function() {
                window.location.href = '{{ route("checkout.index") }}';
            });
            @else
            saveIntendedAndLogin('checkout', productId, 1);
            @endauth
        });
    </script>


    {{-- ── Pusher realtime: product updates on frontend ── --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
    (function() {
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

            var ch = pusher.subscribe('products');

            // New product added — prepend to product grid if visible
            ch.bind('product.created', function(data) {
                // Show toast notification to user
                if (typeof showToast === 'function') {
                    showToast('New product available: ' + data.name, 'info');
                }
                // If product-carousel or new-product-area is on this page, mark for reload
                if ($('.product-carousel-active').length || $('.new-product-area').length) {
                    // Soft reload: show a refresh banner
                    if (!$('#new-product-banner').length) {
                        $('body').prepend(
                            '<div id="new-product-banner" style="position:fixed;top:0;left:0;right:0;z-index:9999;background:#2ecc71;color:#fff;text-align:center;padding:10px;font-size:14px;cursor:pointer;" onclick="location.reload()">' +
                            '✨ New products just added! <u>Click to refresh</u>' +
                            '</div>'
                        );
                    }
                }
            });

            // Product updated — update price/name on page if present
            ch.bind('product.updated', function(data) {
                var $card = $('[data-product-id="' + data.id + '"]');
                if ($card.length && data.price) {
                    $card.find('.hover-product-price').text('LKR ' + parseFloat(data.price).toLocaleString('en', {minimumFractionDigits: 2}));
                }
            });

            // Product deleted — fade out if on page
            ch.bind('product.deleted', function(data) {
                if (data.id) {
                    $('[data-product-id="' + data.id + '"]').fadeOut(400);
                }
            });

        } catch(e) {
            // Pusher not running — silent fail
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>