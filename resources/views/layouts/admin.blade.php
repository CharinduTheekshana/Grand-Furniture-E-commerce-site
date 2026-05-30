<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Grand Furniture</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons-icon-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nivo-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Realtime Toast --}}
    <style>
        .rt-toast {
            position: fixed; bottom: 20px; right: 20px; z-index: 99999;
            background: #333; color: #fff; padding: 12px 20px;
            border-radius: 4px; font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.3); display: none;
        }
        .rt-toast.success { background: #2ecc71; }
        .rt-toast.info    { background: #3498db; }
    </style>

    @stack('styles')
</head>
<body>

    @include('components.header')
    @include('admin.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <div class="rt-toast" id="rt-toast"></div>

    <script src="{{ asset('assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.meanmenu.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrolly.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function showToast(msg, type) {
            $('#rt-toast').removeClass('success info').addClass(type||'info').text(msg).fadeIn(300);
            setTimeout(function(){ $('#rt-toast').fadeOut(400); }, 3500);
        }

        try {
            var pusher = new Pusher('{{ env("PUSHER_APP_KEY","local") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER","mt1") }}',
                wsHost: '{{ env("PUSHER_HOST","127.0.0.1") }}',
                wsPort: {{ env("PUSHER_PORT",6001) }},
                forceTLS: false, enabledTransports: ['ws','wss'],
            });
            var ch = pusher.subscribe('admin-dashboard');
            ch.bind('order.placed', function(d){
                showToast('🛒 New Order #' + d.id + ' — LKR ' + parseFloat(d.total).toLocaleString(), 'success');
            });
            ch.bind('product.created', function(d){
                showToast('✅ Product added: ' + d.name, 'success');
            });
        } catch(e) {}
    </script>

    @stack('scripts')
</body>
</html>
