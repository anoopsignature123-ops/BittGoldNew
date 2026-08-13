<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · BittGold</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('siteadmin/images/fav1.png') }}">
    <style>
        :root { --gold: #efbd15; --panel: #091725; --text: #eef3f8; --muted: #aebbc8; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; overflow: hidden; color: var(--text); font-family: Nunito, "Segoe UI", sans-serif; background: linear-gradient(120deg, rgba(1, 8, 14, .92), rgba(2, 8, 13, .76)), url('{{ asset('assets/images/gold-market-bg.png') }}') center/cover; }
        .error-card { position: relative; width: min(92vw, 570px); padding: 3rem; overflow: hidden; text-align: center; border: 1px solid rgba(239, 189, 21, .46); border-radius: 20px; background: linear-gradient(145deg, rgba(13, 33, 51, .96), rgba(5, 15, 25, .98)); box-shadow: 0 25px 65px rgba(0, 0, 0, .45); }
        .error-card::before { content: ""; position: absolute; inset: 0 0 auto; height: 3px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .brand { display: inline-flex; align-items: center; gap: .55rem; margin-bottom: 1.9rem; color: #fff; font-weight: 800; text-decoration: none; font-size: 1.08rem; }
        .brand img { width: 34px; height: 34px; object-fit: contain; }
        .brand span { color: var(--gold); }
        .error-icon { width: 66px; height: 66px; display: grid; place-items: center; margin: 0 auto 1rem; border: 1px solid rgba(239, 189, 21, .46); border-radius: 50%; color: var(--gold); background: rgba(239, 189, 21, .08); font-size: 2rem; }
        .error-code { margin: 0; color: var(--gold); font-size: clamp(3.8rem, 13vw, 6rem); font-weight: 900; line-height: 1; letter-spacing: -.07em; }
        h1 { margin: .75rem 0 .5rem; font-size: 1.5rem; }
        p { max-width: 420px; margin: 0 auto 1.6rem; color: var(--muted); line-height: 1.65; }
        .error-actions { display: flex; justify-content: center; gap: .7rem; flex-wrap: wrap; }
        .button { display: inline-flex; align-items: center; gap: .45rem; padding: .72rem 1rem; border: 1px solid rgba(239, 189, 21, .4); border-radius: 8px; color: #12100b; background: linear-gradient(135deg, #ffda64, #e5a714); font-weight: 800; text-decoration: none; }
        .button.secondary { color: #dce5ee; background: transparent; }
        @media (max-width: 520px) { .error-card { padding: 2.2rem 1.3rem; } }
    </style>
</head>
<body>
    <main class="error-card">
        <a class="brand" href="{{ route('website.index') }}"><img src="{{ asset('siteadmin/images/fav1.png') }}" alt="BittGold"><strong>Bitt<span>Gold</span></strong></a>
        <div class="error-icon"><i class="mdi @yield('icon')"></i></div>
        <div class="error-code">@yield('code')</div>
        <h1>@yield('title')</h1>
        <p>@yield('message')</p>
        <div class="error-actions">
            <a class="button" href="{{ route('website.index') }}"><i class="mdi mdi-home-outline"></i> Go to Home</a>
            <a class="button secondary" href="javascript:history.back()"><i class="mdi mdi-arrow-left"></i> Go Back</a>
        </div>
    </main>
</body>
</html>
