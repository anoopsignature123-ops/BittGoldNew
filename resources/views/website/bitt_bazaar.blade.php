<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Contact Us - Bitt Gold
    </title>
    <!--=====FAB ICON=======-->
    <link rel="shortcut icon" href="{{ asset('siteadmin/images/titel2.png') }}" type="image/x-icon" />
    <!--=====CSS=======-->
    <link rel="stylesheet" href="{{ asset('siteadmin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/slick-slider.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/mobile-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('siteadmin/css/anni.css') }}" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--=====JQUERY=======-->
    <script src="{{ asset('siteadmin/js/jquery-3-7-1.min.js') }}"></script>
    <script src="{{ asset('siteadmin/js/particles.js') }}"></script>
    <style>
        #particles-js {
            position: absolute !important;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        /* =========================================================
           CONTACT PAGE (page-specific styles, matches gold theme)
        ========================================================= */
        .contact-hero {
            position: relative;
            padding: 180px 0 100px;
            background-color: #0d0d0d;
            overflow: hidden;
            text-align: center;
            isolation: isolate;
        }

        /* Same gold background image used on homepage hero */
        .contact-hero .gold-background {
            position: absolute;  
            background-image: url('{{ asset('siteadmin/images/gold_bars.png') }}');
            background-size: cover;
            background-position: center right;
            opacity: 0.35;
            z-index: -2;
        }

        .contact-hero .gold-glow {
            position: absolute;
            top: -40%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(220, 174, 63, 0.35) 0%, rgba(220, 174, 63, 0) 70%);
            z-index: -1;
            pointer-events: none;
        }

        .contact-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(13, 13, 13, 0.55) 0%, rgba(13, 13, 13, 0.92) 100%);
            z-index: -1;
        }

        .contact-hero h1 {
            font-size: 42px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 15px;
        }

        .contact-hero h1 span {
            color: #dcae3f;
        }

        .contact-hero p {
            color: #b9b9b9;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-section {
            padding: 90px 0;
            background-color: #111111;
        }

        .contact-info-card {
            background: #1a1a1a;
            border: 1px solid rgba(220, 174, 63, 0.15);
            border-radius: 16px;
            padding: 35px 30px;
            height: 100%;
            transition: 0.3s ease;
        }

        .contact-info-card:hover {
            border-color: #dcae3f;
            transform: translateY(-5px);
        }

        .contact-info-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: rgba(220, 174, 63, 0.12);
            color: #dcae3f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .contact-info-card h4 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .contact-info-card p,
        .contact-info-card a {
            color: #b9b9b9;
            text-decoration: none;
            margin: 0;
        }

        .contact-info-card a:hover {
            color: #dcae3f;
        }

        .contact-form-wrap {
            background: #1a1a1a;
            border: 1px solid rgba(220, 174, 63, 0.15);
            border-radius: 20px;
            padding: 45px;
        }

        .contact-form-wrap h3 {
            color: #fff;
            font-size: 26px;
            margin-bottom: 8px;
        }

        .contact-form-wrap p {
            color: #b9b9b9;
            margin-bottom: 30px;
        }

        .contact-form-wrap label {
            color: #d9d9d9;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .contact-form-wrap .form-control {
            background: #111111;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
        }

        .contact-form-wrap .form-control::placeholder {
            color: #7a7a7a;
        }

        .contact-form-wrap .form-control:focus {
            border-color: #dcae3f;
            box-shadow: none;
            background: #111111;
            color: #fff;
        }

        .contact-form-wrap .form-control.is-invalid {
            border-color: #ff6b6b;
            background: #111111;
            color: #fff;
        }

        .contact-form-wrap .form-control.is-invalid:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
            background: #111111;
            color: #fff;
        }

        .contact-form-wrap textarea.form-control {
            min-height: 150px;
            resize: none;
        }

        .contact-submit-btn {
            background: linear-gradient(90deg, #dcae3f, #f5d780);
            border: none;
            color: #1a1a1a;
            font-weight: 600;
            padding: 13px 34px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s ease;
        }

        .contact-submit-btn:hover {
            opacity: 0.9;
            color: #1a1a1a;
        }

        .contact-map-wrap {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(220, 174, 63, 0.15);
            margin-top: 60px;
        }

        .contact-map-wrap iframe {
            width: 100%;
            height: 380px;
            border: 0;
            filter: grayscale(1) invert(0.9) contrast(0.85);
        }

        @media (min-width: 768px) and (max-width: 1024px) {

            .btn_theme {
                padding: 4px 5px;
            }

        }

        .contact-form-wrap {
            background: #050505;
            border: 1px solid rgba(212, 175, 55, 0.5);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.25), 0 0 25px rgba(212, 175, 55, 0.18), 0 0 50px rgba(212, 175, 55, 0.10);
            background: #1a1a1a;
            border: 1px solid rgba(220, 174, 63, 0.15);
            border-radius: 20px;
            padding: 45px;
        }

        .contact-info-card {
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.25), 0 0 25px rgb(212 175 55 / 0%), 0 0 50px rgba(212, 175, 55, 0.10);
            background: #1a1a1a;
            background: #1a1a1a;
            border: 1px solid rgba(220, 174, 63, 0.15);
            border-radius: 16px;
            padding: 35px 30px;
            height: 100%;
            transition: 0.3s ease;
        }

        @media (max-width: 991px) {
            .contact-hero {
                padding: 140px 0 70px;
            }
        }
    </style>
</head>

<body class="body body2">

    <!--=====progress END=======-->
    <!-- Preloader Start -->
    <div class="preloader preloader2">
        <div class="loading-container">
            <div class="loading loading2">
            </div>
            <div id="loading-icon">
                <img src="{{ asset('siteadmin/images/preloader-logo2.png') }}" alt="" />
            </div>
        </div>
    </div>
    <!-- Preloader End -->
    <div class="paginacontainer">
        <div class="progress-wrap progress-wrap2">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98">
                </path>
            </svg>
        </div>
    </div>
    <!--=====progress END=======-->
    <!--=====HEADER START=======-->
    <header>
        <div class="header-area header-area2 single-header2 header-area-all d-none d-lg-block" id="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="header-elements">
                            <div class="site-logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('siteadmin/images/logo.png') }}" alt="" class="logomin">
                                </a>
                            </div>
                            <div class="main-menu-ex main-menu-ex1">
                                <ul id="list-example">
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="{{ url('/') }}">
                                            Home
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="{{ url('/#about') }}">
                                            About
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action"
                                            href="{{ url('/#gold_nvestment') }}">
                                            Gold Investment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action"
                                            href="{{ url('/#live_prices') }}">
                                            Live Prices
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action"
                                            href="{{ url('/#market_nsights') }}">
                                            Market Insights
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action active"
                                            href="{{ route('contact') }}">
                                            Contact Us
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="header2-buttons">
                                <div class="button">
                                    <a href="{{ route('user.login') }}"
                                        class="btn_theme btn_theme_active mt_40 wow fadeInDown"
                                        data-wow-duration="0.8s">
                                        <i class="fa-solid fa-user"></i>Sign In
                                        <span></span>
                                    </a>
                                    <a href="{{ route('user.register') }}"
                                        class="btn_theme btn_theme_active mt_40 wow fadeInDown ms-2"
                                        data-wow-duration="0.8s">
                                        <i class="fa-solid fa-user"></i>Sign Up
                                        <span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--=====HEADER END=======-->
    <!--=====Mobile header start=======-->
    <div class="mobile-header mobile-header2 mobile-header-main d-block d-lg-none ">
        <div class="container-fluid">
            <div class="col-12">
                <div class="mobile-header-elements">
                    <div class="mobile-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('siteadmin/images/logo.png') }}" alt="" class="logomin">
                        </a>
                    </div>
                    <div class="mobile-nav-icon">
                        <i class="fa-duotone fa-bars-staggered">
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-sidebar mobile-sidebar2 d-block d-lg-none">
        <div class="logo-m">
            <a href="{{ url('/') }}">
                <img src="{{ asset('siteadmin/images/logo.png') }}" class="logomin" alt="">
            </a>
        </div>
        <div class="menu-close">
            <i class="fa-solid fa-xmark">
            </i>
        </div>
        <div class="mobile-nav">
            <ul>
                <li>
                    <a href="{{ url('/') }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ url('/#about') }}">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="{{ url('/#gold_nvestment') }}">
                        Gold Investment
                    </a>
                </li>
                <li>
                    <a href="{{ url('/#live_prices') }}">
                        Live Prices
                    </a>
                </li>
                <li>
                    <a href="{{ url('/#market_nsights') }}">
                        Market Insights
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}">
                        Contact Us
                    </a>
                </li>
            </ul>
            <div class="mobile-button">
                <a href="{{ route('user.login') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown"
                    data-wow-duration="0.8s">
                    Login
                    <i class="fa-solid fa-arrow-right">
                    </i>
                    <span>
                    </span>
                </a>
                <a href="{{ route('user.register') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown"
                    data-wow-duration="0.8s">
                    Register
                    <i class="fa-solid fa-arrow-right">
                    </i>
                    <span>
                    </span>
                </a>
            </div>
            <div class="single-footer-items">
                <h3>
                    Contact Us
                </h3>
                <div class="contact-box">
                    <div class="pera">
                        <a href="mailto:info@bittgold.com">
                            info@bittgold.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=====Mobile header end=======-->
    <section class="bitgold-hero bb-coming-soon">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 text-center">
                    <!-- Logo -->
                    <div class="bb-logo">
                        <img src="{{ asset('siteadmin/images/bitt_bazaar.png') }}"
                             alt="Bitt Bazaar - Everything You Need, One Place"
                             class="img-fluid">
                    </div>
                    <!-- Coming Soon Text -->
                    <div class="bb-coming-content">
                        <span class="bb-tag text-light">SOMETHING BIG IS COMING</span>
                        <h1 class="text-light">
                            COMING <span>SOON</span>
                        </h1>
                        <div class="bb-divider"></div>
                        <p class="text-light">
                            Everything You Need, One Place.
                            Your Favourite Shopping Destination Is Almost Here!
                        </p>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--main-content-end-->
    <!--===== FOOTER AREA START =======-->
    <div class="footer2 _relative" id="contact">

        <!-- Copyright / Footer Bottom -->
        <div class="copyright-area" id="contact">
            <div class="container-fluid">
                <div class="copyright-inner">
                    <!-- Left -->
                    <div class="copyright-left">
                        <a href="{{ url('/') }}" class="footer-logo">
                            <img src="{{ asset('siteadmin/images/biticon.png') }}" alt="Bitt Gold">
                        </a>
                        <p>
                            © 2026 Bitt Gold. All Rights Reserved.
                        </p>
                    </div>
                    <!-- Right -->
                    <div class="copyright-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Use</a>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('siteadmin/images/footer2-element.png') }}" alt="" class="shape">
    </div>



    <!--===== FOOTER AREA END =======-->
    <script src="{{ asset('siteadmin/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('siteadmin/js/aos.js') }}"></script>
    <script src="{{ asset('siteadmin/js/fontawesome.js') }}"></script>
    <script src="{{ asset('siteadmin/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('siteadmin/js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('siteadmin/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('siteadmin/js/jquery.countup.js') }}"></script>
    <script src="{{ asset('siteadmin/js/slick-slider.js') }}"></script>
    <script src="{{ asset('siteadmin/js/gsap.min.js') }}"></script>
    <script src="{{ asset('siteadmin/js/jquery.nice-select.js') }}"></script>
    <script src="{{ asset('siteadmin/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('siteadmin/js/Splitetext.js') }}"></script>
    <script src="{{ asset('siteadmin/js/SmoothScroll.js') }}"></script>
    <script src="{{ asset('siteadmin/js/text-animation.js') }}"></script>
    <script src="{{ asset('siteadmin/js/switchmode.js') }}"></script>
    <script src="{{ asset('siteadmin/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('siteadmin/js/swiper.js') }}"></script>
    <script src="{{ asset('siteadmin/js/jquery.lineProgressbar.js') }}"></script>
    <script src="{{ asset('siteadmin/js/tilt.jquery.js') }}"></script>
    <script src="{{ asset('siteadmin/js/animation.js') }}"></script>
    <script src="{{ asset('siteadmin/js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
