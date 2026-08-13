<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BittGold - @stack('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . time()) }}">
    <link rel="icon" type="image/png" href="{{ asset('siteadmin/images/titel2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('siteadmin/images/titel2.png') }}">
    <style>
        /* hard-stop any global img rules stretching logo */
        .auth-center-logo img { width:120px!important; height:auto!important; max-width:120px!important; display:block; }
    </style>
</head>

<body class="auth-page">
    <div id="global-loader" class="global-loader" role="status">
        <div class="loader-card"><span class="loader-mark"><i class="mdi mdi-chart-line"></i></span><span>Loading...</span></div>
    </div>
    <div id="toast-container" class="toast-container" aria-live="polite"></div>

    <main class="auth-shell-center">

    

        {{-- Form card — wider on register page --}}
        <div class="auth-center-card @stack('card_class')">
            @yield('content')
        </div>

    </main>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/bill-gold-ui.js') }}"></script>
    <script>
        document.querySelectorAll('.password-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var inp = btn.parentElement.querySelector('input');
                inp.type = inp.type === 'password' ? 'text' : 'password';
                btn.querySelector('i').className = inp.type === 'password'
                    ? 'mdi mdi-eye-outline' : 'mdi mdi-eye-off-outline';
            });
        });
    </script>
    <script>
        @if (session('success'))
            BillGold.success(@json(session('success')));
        @elseif (session('error'))
            BillGold.error(@json(session('error')));
        @elseif (session('warning'))
            BillGold.toast('warning', @json(session('warning')));
        @elseif (session('info'))
            BillGold.toast('info', @json(session('info')));
        @elseif ($errors->any())
            BillGold.error(@json($errors->first()));
        @endif
    </script>
    @stack('scripts')
</body>
</html>
