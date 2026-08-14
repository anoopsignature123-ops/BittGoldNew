<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Bitt Gold
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
                                <a href="index.html">
                                    <img src="{{ asset('siteadmin/images/logo.png') }}" alt="" class="logomin">
                                </a>
                            </div>
                            <div class="main-menu-ex main-menu-ex1">
                                <ul id="list-example">
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="#">
                                            Home
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="#about">
                                            About
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="#gold_nvestment">
                                            Gold Investment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="#live_prices">
                                            Live Prices
                                        </a>
                                    </li>
                                    <li>
                                        <a class="list-group-item list-group-item-action" href="#market_nsights">
                                            Market Insights
                                        </a>
                                    </li>
                                    <!--<li>
                                        <a class="list-group-item list-group-item-action" href="#resources">
                                            Resources
                                        </a>
                                    </li>-->
                                    <li>
                                        <a class="list-group-item list-group-item-action" 
                                        href="{{ route('contact') }}">
                                            Contact Us
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="header2-buttons">
                                <div class="button">
                                    <a href="{{ route('user.login') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown"
                                        data-wow-duration="0.8s">
                                        <i class="fa-solid fa-user"></i>Sign In
                                        <span></span>
                                    </a>
                                    <a href="{{ route('user.register') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown ms-2"
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
                        <a href="index1.html">
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
            <a href="index.html">
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
                    <a href="index.html">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#about">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="#gold_nvestment">
                        Gold Investment
                    </a>
                </li>
                <li>
                    <a href="#live_prices">
                        Live Prices
                    </a>
                </li>
                <li>
                    <a href="#market_nsights">
                        Market Insights
                    </a>
                </li>
                <!--<li>
                    <a href="#resources">
                        Resources
                    </a>
                </li>-->

                <li>
                    <a href="{{ route('contact') }}">
                        Contact Us
                    </a>
                </li>
            </ul>
            <div class="mobile-button">
                <a href="{{ route('user.login') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown" data-wow-duration="0.8s">
                    Login
                    <i class="fa-solid fa-arrow-right">
                    </i>
                    <span>
                    </span>
                </a>
                 <a href="{{ route('user.register') }}" class="btn_theme btn_theme_active mt_40 wow fadeInDown" data-wow-duration="0.8s">
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



    <!--main-content-start-->


    <!-- ==========BITTGOLD HERO====== -->
    <section class="bitgold-hero">
        <!-- Bubble Background -->
        <div class="bubble-bg">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- GOLD BACKGROUND IMAGE -->
        <div class="gold-background"></div>

        <!-- GOLD GLOW -->
        <div class="gold-glow"></div>

        <div class="container bitgold-container">

            <!-- =====================================================
                 HERO CONTENT
            ====================================================== -->
            <div class="hero-content">

                <!-- Label -->
                <span class="hero-label">
                    Smart Investments.
                </span>

                <!-- Heading -->
                <h1 class="hero-heading">
                    Timeless <span>Value.</span>
                </h1>

                <!-- Description -->
                <p class="hero-description">
                    BittGold offers a trusted platform to invest in
                    gold and build wealth for a secure future.
                </p>

                <!-- =================================================
                     FEATURES
                ================================================== -->
                <div class="hero-features">

                    <!-- Secure -->
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="feature-text">
                            <strong>
                                Secure
                            </strong>
                            <small>
                                Investments
                            </small>
                        </div>
                    </div>

                    <!-- Real Time -->
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="feature-text">
                            <strong>
                                Real-time
                            </strong>
                            <small>
                                Gold Prices
                            </small>
                        </div>
                    </div>

                    <!-- Expert -->
                    <div class="hero-feature">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <div class="feature-text">
                            <strong>
                                Expert Market
                            </strong>
                            <small>
                                Insights
                            </small>
                        </div>
                    </div>

                </div>

                <!-- =================================================
                     BUTTONS
                ================================================== -->
                <div class="hero-buttons">
                    <a href="{{ route('user.investment.index') }}" class="btn-invest">
                        Start Investing
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('user.investment.index') }}" class="btn-explore">
                        Explore Gold Plans
                    </a>
                </div>
            </div>

            <!-- =====================================================
                 PRICE CARD
            ====================================================== -->
            <div class="price-card">

                <div class="price-title">
                    Gold Price Today
                </div>

                <div class="price-value">
                    ₹ 6,245
                    <span>/gm</span>
                </div>

                <div class="price-footer">

                    <div class="price-quality">
                        24K (995)
                    </div>

                    <div class="price-change">
                        <i class="bi bi-arrow-up"></i>
                        1.28%
                    </div>

                </div>

                <!-- Gold Price Graph -->
                <div class="price-graph">
                    <svg viewBox="0 0 130 55" preserveAspectRatio="none">

                        <polyline
                            points="2,45 12,42 22,46 32,35 42,39 52,28 62,32 72,23 82,27 92,18 102,22 112,10 122,15 128,7"
                            fill="none" stroke="#f5b82e" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" />

                        <circle cx="22" cy="46" r="1.5" />
                        <circle cx="32" cy="35" r="1.5" />
                        <circle cx="52" cy="28" r="1.5" />
                        <circle cx="72" cy="23" r="1.5" />
                        <circle cx="92" cy="18" r="1.5" />
                        <circle cx="112" cy="10" r="1.5" />
                        <circle cx="128" cy="7" r="1.5" />

                    </svg>
                </div>

            </div>

            <!-- =====================================================
                 STATS BAR
            ====================================================== -->
            <div class="stats-wrapper">

                <div class="stats-bar">

                    <!-- =================================================
                         STAT 1
                    ================================================== -->
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>

                        <div class="stat-content">
                            <strong>
                                100%
                            </strong>
                            <span>
                                Secure Storage
                            </span>
                        </div>
                    </div>

                    <!-- =================================================
                         STAT 2
                    ================================================== -->
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div class="stat-content">
                            <strong>
                                24/7
                            </strong>
                            <span>
                                Market Access
                            </span>
                        </div>
                    </div>

                    <!-- =================================================
                         STAT 3
                    ================================================== -->
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div class="stat-content">
                            <strong>
                                Low Cost
                            </strong>
                            <span>
                                Transparent Pricing
                            </span>
                        </div>
                    </div>

                    <!-- =================================================
                         STAT 4
                    ================================================== -->
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-person-check"></i>
                        </div>

                        <div class="stat-content">
                            <strong>
                                Trusted by
                            </strong>
                            <span>
                                Thousands of Investors
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <!--=====HERO AREA END=======-->


    <!-- =========================================================
         WHY INVEST IN GOLD SECTION
    ========================================================= -->
    <section class="why-gold-section" style="background-color: #1a1a1a" id="aboutInvestment Services">
        <div class="container">
            <!-- =====================================================
         HEADER
    ====================================================== -->
            <div class="why-gold-header">
                <h2 class="why-gold-title">
                    Why Invest <span>in Gold?</span>
                </h2>

                <p class="why-gold-description mb-5">
                    Gold has been a symbol of wealth and security for centuries.
                    <br class="d-none d-sm-block">
                    It continues to be one of the most reliable investments.

                </p>
            </div>

            <!-- =====================================================
         FEATURES
    ====================================================== -->
            <div class="why-gold-features">

                <!-- =================================================
             01
        ================================================== -->
                <div class="why-gold-item">
                    <div class="why-gold-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <h3>
                        Hedge Against<br>
                        Inflation
                    </h3>

                    <p>
                        Protect your wealth from
                        inflation and economic
                        uncertainty.
                    </p>
                </div>

                <!-- =================================================
             02
        ================================================== -->
                <div class="why-gold-item">
                    <div class="why-gold-icon">
                        <i class="bi bi-globe2"></i>
                    </div>

                    <h3>
                        Portfolio<br>
                        Diversification
                    </h3>

                    <p>
                        Diversify your portfolio
                        and reduce overall
                        investment risk.
                    </p>
                </div>

                <!-- =================================================
             03
        ================================================== -->
                <div class="why-gold-item">
                    <div class="why-gold-icon">
                        <i class="bi bi-bank"></i>
                    </div>

                    <h3>
                        High Liquidity
                    </h3>

                    <p>
                        Easily buy or sell gold
                        with 24/7 market
                        access.
                    </p>
                </div>

                <!-- =================================================
             04
        ================================================== -->
                <div class="why-gold-item">
                    <div class="why-gold-icon">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>

                    <h3>
                        Long-term<br>
                        Wealth
                    </h3>

                    <p>
                        A stable long-term
                        investment with
                        historical value growth.
                    </p>
                </div>

                <!-- =================================================
             05
        ================================================== -->
                <div class="why-gold-item">
                    <div class="why-gold-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <h3>
                        Safe &amp; Secure
                    </h3>

                    <p>
                        Your gold is stored
                        safely in insured
                        vaults.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- =========================================================
         OUR GOLD INVESTMENT SERVICES
    ========================================================= -->
    <section class="gold-services-section" id="gold_nvestment">
        <!-- Bubble Background -->
        <div class="bubble-bg">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="container">

            <!-- =====================================================
                 HEADING
            ====================================================== -->
            <div class="services-heading">
                <h2>
                    Our <span>Gold</span> Investment Services
                </h2>
            </div>

            <!-- =====================================================
                 SERVICES
            ====================================================== -->
            <div class="row g-3 services-row">

                <!-- =================================================
                     CARD 1 - PHYSICAL GOLD
                ================================================== -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="gold-service-card">

                        <div class="service-image">
                            <img src="{{ asset('siteadmin/images/physical-gold.png') }}" alt="Physical Gold">
                        </div>

                        <div class="service-content">
                            <h3>
                                Physical Gold
                            </h3>

                            <p>
                                Invest in 24K 999.9 purity gold
                                bars &amp; coins with certified
                                assurance.
                            </p>

                            <a href="#" class="learn-more">
                                Learn More

                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- =================================================
                     CARD 2 - DIGITAL GOLD
                ================================================== -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="gold-service-card">

                        <div class="service-image">
                            <img src="{{ asset('siteadmin/images/digital-gold.png') }}" alt="Digital Gold">
                        </div>

                        <div class="service-content">
                            <h3>
                                Digital Gold
                            </h3>

                            <p>
                                Buy &amp; sell digital gold anytime,
                                anywhere with complete
                                transparency.
                            </p>

                            <a href="#" class="learn-more">
                                Learn More

                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- =================================================
                     CARD 3 - GOLD SAVINGS
                ================================================== -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="gold-service-card">

                        <div class="service-image">
                            <img src="{{ asset('siteadmin/images/gold-savings.png') }}" alt="Gold Savings Plans">
                        </div>

                        <div class="service-content">
                            <h3>
                                Gold Savings Plans
                            </h3>

                            <p>
                                Start with small amounts &amp;
                                build your wealth with
                                flexible plans.
                            </p>

                            <a href="#" class="learn-more">
                                Learn More

                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- =================================================
                     CARD 4 - GOLD STORAGE
                ================================================== -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="gold-service-card">

                        <div class="service-image">
                            <img src="{{ asset('siteadmin/images/gold-storage.png') }}" alt="Gold Storage">
                        </div>

                        <div class="service-content">
                            <h3>
                                Gold Storage
                            </h3>

                            <p>
                                Secure &amp; insured vault storage
                                for your gold with full
                                protection.
                            </p>

                            <a href="#" class="learn-more">
                                Learn More

                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- =========================================================
         GOLD PERFORMANCE SECTION
    ========================================================= -->
    <section class="gold-performance-section" id="market_nsights">
        <div class="container">
            <div class="performance-box">

                <!-- =================================================
                     INTRO
                ================================================== -->
                <div class="performance-intro">
                    <h2>
                        <span>Gold:</span>
                        A Proven<br>
                        Performer
                    </h2>

                    <p>
                        Gold has consistently delivered
                        strong returns over the long term.
                    </p>

                    <a href="#" class="market-btn">
                        <span>
                            View Market Insights
                        </span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- =================================================
                     1 YEAR
                ================================================== -->
                <div class="performance-item">
                    <span class="performance-year">
                        1 Year
                    </span>
                    <span class="performance-percent">
                        +18.45%
                    </span>

                    <div class="performance-chart">
                        <svg viewBox="0 0 180 55" preserveAspectRatio="none">
                            <!-- Glow -->
                            <polyline class="chart-glow"
                                points="
                                      0,48
                                      8,45
                                      15,47
                                      23,40
                                      30,42
                                      37,32
                                      44,36
                                      52,29
                                      59,33
                                      66,25
                                      74,29
                                      82,20
                                      90,24
                                      98,18
                                      106,22
                                      114,14
                                      122,19
                                      130,12
                                      138,15
                                      146,9
                                      155,13
                                      165,7
                                      180,5
                                      " />
                            <!-- Main line -->
                            <polyline class="chart-line"
                                points="
                                      0,48
                                      8,45
                                      15,47
                                      23,40
                                      30,42
                                      37,32
                                      44,36
                                      52,29
                                      59,33
                                      66,25
                                      74,29
                                      82,20
                                      90,24
                                      98,18
                                      106,22
                                      114,14
                                      122,19
                                      130,12
                                      138,15
                                      146,9
                                      155,13
                                      165,7
                                      180,5
                                      " />
                            <circle class="chart-dot" cx="180" cy="5" r="2" />
                        </svg>
                    </div>
                </div>

                <!-- =================================================
                     3 YEARS
                ================================================== -->
                <div class="performance-item">
                    <span class="performance-year">
                        3 Years
                    </span>
                    <span class="performance-percent">
                        +42.30%
                    </span>

                    <div class="performance-chart">
                        <svg viewBox="0 0 180 55" preserveAspectRatio="none">
                            <polyline class="chart-glow"
                                points="
                                      0,48
                                      9,45
                                      17,47
                                      25,38
                                      32,41
                                      40,31
                                      48,35
                                      56,28
                                      63,31
                                      71,23
                                      79,26
                                      87,18
                                      95,22
                                      103,15
                                      111,18
                                      119,12
                                      127,14
                                      136,8
                                      145,11
                                      154,5
                                      164,9
                                      173,3
                                      180,1
                                      " />
                            <polyline class="chart-line"
                                points="
                                      0,48
                                      9,45
                                      17,47
                                      25,38
                                      32,41
                                      40,31
                                      48,35
                                      56,28
                                      63,31
                                      71,23
                                      79,26
                                      87,18
                                      95,22
                                      103,15
                                      111,18
                                      119,12
                                      127,14
                                      136,8
                                      145,11
                                      154,5
                                      164,9
                                      173,3
                                      180,1
                                      " />
                            <circle class="chart-dot" cx="180" cy="1" r="2" />
                        </svg>
                    </div>
                </div>

                <!-- =================================================
                     5 YEARS
                ================================================== -->
                <div class="performance-item">
                    <span class="performance-year">
                        5 Years
                    </span>
                    <span class="performance-percent">
                        +68.75%
                    </span>

                    <div class="performance-chart">
                        <svg viewBox="0 0 180 55" preserveAspectRatio="none">
                            <polyline class="chart-glow"
                                points="
                                      0,48
                                      8,45
                                      16,47
                                      23,36
                                      31,40
                                      39,29
                                      47,34
                                      55,25
                                      64,29
                                      72,19
                                      80,24
                                      89,16
                                      97,20
                                      105,12
                                      113,16
                                      122,9
                                      130,13
                                      139,7
                                      147,10
                                      156,4
                                      165,8
                                      173,2
                                      180,0
                                      " />
                            <polyline class="chart-line"
                                points="
                                      0,48
                                      8,45
                                      16,47
                                      23,36
                                      31,40
                                      39,29
                                      47,34
                                      55,25
                                      64,29
                                      72,19
                                      80,24
                                      89,16
                                      97,20
                                      105,12
                                      113,16
                                      122,9
                                      130,13
                                      139,7
                                      147,10
                                      156,4
                                      165,8
                                      173,2
                                      180,0
                                      " />
                            <circle class="chart-dot" cx="180" cy="0" r="2" />
                        </svg>
                    </div>
                </div>

                <!-- =================================================
                     10 YEARS
                ================================================== -->
                <div class="performance-item">
                    <span class="performance-year">
                        10 Years
                    </span>
                    <span class="performance-percent">
                        +112.60%
                    </span>

                    <div class="performance-chart">
                        <svg viewBox="0 0 180 55" preserveAspectRatio="none">
                            <polyline class="chart-glow"
                                points="
                                      0,48
                                      9,44
                                      17,46
                                      25,37
                                      33,41
                                      41,30
                                      49,35
                                      57,26
                                      65,30
                                      73,21
                                      81,25
                                      90,17
                                      98,21
                                      106,13
                                      114,17
                                      122,10
                                      130,14
                                      138,7
                                      147,11
                                      155,5
                                      164,8
                                      172,2
                                      180,0
                                      " />
                            <polyline class="chart-line"
                                points="
                                      0,48
                                      9,44
                                      17,46
                                      25,37
                                      33,41
                                      41,30
                                      49,35
                                      57,26
                                      65,30
                                      73,21
                                      81,25
                                      90,17
                                      98,21
                                      106,13
                                      114,17
                                      122,10
                                      130,14
                                      138,7
                                      147,11
                                      155,5
                                      164,8
                                      172,2
                                      180,0
                                      " />
                            <circle class="chart-dot" cx="180" cy="0" r="2" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>





    <!-- ==========HOW BITTGOLD WORKS start============== -->
    <!-- =========================================================
         HTML
    ========================================================= -->
    <section class="bittgold-section mt-5" style="background-color: #1a1a1a" id="live_prices">
        <!-- Bubble Background -->
        <div class="bubble-bg">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="container-fluid px-3 px-lg-4 mt-5 mb-5">
            <div class="bittgold-inner">

                <!-- =================================================
                     TITLE
                ================================================== -->
                <h2 class="works-heading">
                    How <span>BittGold</span> Works
                </h2>

                <!-- =================================================
                     WORKS TIMELINE
                ================================================== -->
                <div class="works-timeline">
                    <div class="timeline-line"></div>

                    <div class="row row-cols-5 g-2">

                        <!-- 1 -->
                        <div class="col">
                            <div class="work-item">
                                <div class="work-icon">
                                    <i class="bi bi-person-add"></i>
                                </div>
                                <div class="work-number">
                                    1
                                </div>
                                <div class="work-name">
                                    Create Account
                                </div>
                                <div class="work-description">
                                    Sign up in minutes<br>
                                    and verify your<br>
                                    identity.
                                </div>
                            </div>
                        </div>

                        <!-- 2 -->
                        <div class="col">
                            <div class="work-item">
                                <div class="work-icon">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                                <div class="work-number">
                                    2
                                </div>
                                <div class="work-name">
                                    Choose Your Plan
                                </div>
                                <div class="work-description">
                                    Select from a range<br>
                                    of gold investment<br>
                                    options.
                                </div>
                            </div>
                        </div>

                        <!-- 3 -->
                        <div class="col">
                            <div class="work-item">
                                <div class="work-icon">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <div class="work-number">
                                    3
                                </div>
                                <div class="work-name">
                                    Invest Securely
                                </div>
                                <div class="work-description">
                                    Make secure payments<br>
                                    using multiple<br>
                                    methods.
                                </div>
                            </div>
                        </div>

                        <!-- 4 -->
                        <div class="col">
                            <div class="work-item">
                                <div class="work-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="work-number">
                                    4
                                </div>
                                <div class="work-name">
                                    Track &amp; Monitor
                                </div>
                                <div class="work-description">
                                    Track real-time gold<br>
                                    prices and your<br>
                                    investments.
                                </div>
                            </div>
                        </div>

                        <!-- 5 -->
                        <div class="col">
                            <div class="work-item">
                                <div class="work-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="work-number">
                                    5
                                </div>
                                <div class="work-name">
                                    Grow Your Wealth
                                </div>
                                <div class="work-description">
                                    Hold, sell or redeem<br>
                                    your gold anytime<br>
                                    you want.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =================================================
                     MARKET CARDS
                ================================================== -->
                <div class="row g-2 market-row mt-5">

                    <!-- =================================================
                         LIVE GOLD MARKET
                    ================================================== -->
                    <div class="col-lg-7">
                        <div class="market-card gold-card">
                            <h3 class="card-heading">
                                Live Gold Market Overview
                            </h3>

                            <!-- PURITY TABS -->
                            <div class="purity-tabs">
                                <button type="button" class="purity-tab active" data-purity="24K">
                                    24K (999)

                                </button>

                                <button type="button" class="purity-tab" data-purity="22K">
                                    22K (916)

                                </button>

                                <button type="button" class="purity-tab" data-purity="18K">
                                    18K (750)

                                </button>
                            </div>

                            <div class="market-body">
                                <!-- =================================================
                                     PRICE
                                ================================================== -->
                                <div class="price-section">
                                    <div class="price-line">
                                        <span class="price-value" id="goldPrice">
                                            ₹ 6,245

                                        </span>
                                        <span class="price-unit">
                                            /gm
                                        </span>
                                    </div>

                                    <div class="price-change" id="goldChange">
                                        ↑ 1.28%

                                    </div>

                                    <div class="change-text">
                                        Today's Change
                                    </div>

                                    <div class="high-low">

                                        <div class="high-low-item">
                                            <span>
                                                High (24H)
                                            </span>
                                            <strong id="goldHigh">
                                                ₹ 6,310
                                            </strong>
                                        </div>

                                        <div class="high-low-item">
                                            <span>
                                                Low (24H)
                                            </span>
                                            <strong id="goldLow">
                                                ₹ 6,150
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- =================================================
                                     GRAPH
                                ================================================== -->

                                <div class="chart-section">

                                    <div class="chart-periods">
                                        <button type="button" class="period-btn active" data-period="1D">
                                            1D

                                        </button>

                                        <button type="button" class="period-btn" data-period="1W">
                                            1W

                                        </button>

                                        <button type="button" class="period-btn" data-period="1M">
                                            1M

                                        </button>

                                        <button type="button" class="period-btn" data-period="1Y">
                                            1Y

                                        </button>
                                    </div>

                                    <div class="chart-wrap">
                                        <svg class="gold-chart" viewBox="0 0 700 100" preserveAspectRatio="none">

                                            <defs>
                                                <linearGradient id="goldArea" x1="0" y1="0"
                                                    x2="0" y2="1">
                                                    <stop offset="0%" stop-color="#dcae3f" stop-opacity=".35" />
                                                    <stop offset="100%" stop-color="#dcae3f" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>

                                            <path id="chartAreaFill" class="chart-area-fill">
                                            </path>

                                            <path id="chartLine" class="chart-line">
                                            </path>

                                            <circle id="chartPoint" class="chart-point" r="3">
                                            </circle>
                                        </svg>

                                        <div class="chart-labels">
                                            <span id="label1">
                                                00:00
                                            </span>
                                            <span id="label2">
                                                06:00
                                            </span>
                                            <span id="label3">
                                                12:00
                                            </span>
                                            <span id="label4">
                                                18:00
                                            </span>
                                            <span id="label5">
                                                24:00
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================
                         MARKET INSIGHTS
                    ================================================== -->
                    <div class="col-lg-5">
                        <div class="market-card insights-card">
                            <h3 class="insights-heading">
                                Market Insights
                            </h3>

                            <!-- Insight 1 -->
                            <div class="insight-item">
                                <div class="insight-icon">
                                    <i class="bi bi-link-45deg"></i>
                                </div>
                                <div class="insight-text">
                                    Global gold prices rise on safe-haven demand

                                    <span class="insight-date">
                                        May 15, 2024
                                    </span>
                                </div>
                            </div>

                            <!-- Insight 2 -->
                            <div class="insight-item">
                                <div class="insight-icon">
                                    <i class="bi bi-globe2"></i>
                                </div>
                                <div class="insight-text">
                                    Central banks increase gold reserves

                                    <span class="insight-date">
                                        May 10, 2024
                                    </span>
                                </div>
                            </div>

                            <!-- Insight 3 -->
                            <div class="insight-item">
                                <div class="insight-icon">
                                    <i class="bi bi-bag"></i>
                                </div>
                                <div class="insight-text">
                                    Inflation concerns drive gold higher

                                    <span class="insight-date">
                                        May 05, 2024
                                    </span>
                                </div>
                            </div>

                            <button type="button" class="insight-button">
                                View All Insights
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================
             GOLD BULL
        ========================================================== -->
        <img src="{{ asset('siteadmin/images/gold-bull.png') }}" class="gold-bull-image" alt="Gold Bull">
    </section>


    <!-- ==========HOW BITTGOLD WORKS end============== -->
    <!-- =========================================
         BITTGOLD CTA SECTION
    ========================================= -->
    <section class="bittgold-cta-section mt-5">
        <div class="container-fluid">
            <div class="bittgold-cta">
                <div class="row align-items-center g-0">
                    <!-- =========================
                         GOLD IMAGE
                    ========================== -->
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="gold-image">
                            <img src="{{ asset('siteadmin/images/gold_bars.png') }}" alt="Gold Investment"
                                class="img-fluid">
                        </div>
                    </div>

                    <!-- =========================
                         CONTENT
                    ========================== -->
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="cta-content">
                            <h2>
                                Ready to Secure Your Future with Gold?
                            </h2>
                            <p>
                                Join thousands of investors who trust BittGold
                                for safe &amp; profitable gold investments.
                            </p>
                        </div>
                    </div>

                    <!-- =========================
                         BUTTON
                    ========================== -->
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="cta-action">
                            <a href="#" class="bittgold-btn">
                                <span>Open Your Account Now</span>
                                <b>→</b>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================
  
    <!--=====CTA IMAGE AREA END=======-->
    <!--main-content-end-->
    <!--===== FOOTER AREA START =======-->
    <div class="footer2 _relative" id="contact">
       
        <!-- Copyright / Footer Bottom -->
        <div class="copyright-area" id="contact">
            <div class="container-fluid">
                <div class="copyright-inner">
                    <!-- Left -->
                    <div class="copyright-left">
                        <a href="#" class="footer-logo">
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
    <script type="module" src="https://widgets.tradingview-widget.com/w/en/tv-ticker-tape.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* =========================================================
       DESKTOP PARALLAX
    ========================================================= */

        const goldBackground =
            document.querySelector(".gold-background");

        const goldGlow =
            document.querySelector(".gold-glow");


        if (window.innerWidth > 991) {

            document.addEventListener("mousemove", function(event) {

                const x =
                    (event.clientX / window.innerWidth) - 0.5;

                const y =
                    (event.clientY / window.innerHeight) - 0.5;


                goldBackground.style.marginLeft =
                    `${x * 10}px`;


                goldGlow.style.marginLeft =
                    `${x * -18}px`;

                goldGlow.style.marginTop =
                    `${y * -12}px`;

            });

        }
    </script>

    <!-- =========================================================
         JAVASCRIPT
    ========================================================= -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            /* =====================================================
               GRAPH DATA
            ===================================================== */

            const graphData = {

                "1D": [
                    6180, 6210, 6170, 6240, 6280,
                    6240, 6310, 6260, 6300, 6250,
                    6290, 6270, 6350, 6300, 6380,
                    6340, 6410, 6380, 6430, 6400,
                    6450, 6420, 6480, 6460, 6520
                ],


                "1W": [
                    6000,
                    6050,
                    6030,
                    6110,
                    6150,
                    6210,
                    6280,
                    6340
                ],


                "1M": [
                    5700,
                    5780,
                    5830,
                    5910,
                    5960,
                    6020,
                    6100,
                    6170,
                    6250,
                    6340
                ],


                "1Y": [
                    5200,
                    5280,
                    5360,
                    5430,
                    5510,
                    5590,
                    5680,
                    5780,
                    5890,
                    6010,
                    6180,
                    6270,
                    6340
                ]

            };


            /* =====================================================
               GRAPH LABELS
            ===================================================== */

            const graphLabels = {

                "1D": [
                    "00:00",
                    "06:00",
                    "12:00",
                    "18:00",
                    "24:00"
                ],

                "1W": [
                    "Mon",
                    "Tue",
                    "Wed",
                    "Thu",
                    "Fri"
                ],

                "1M": [
                    "Week 1",
                    "Week 2",
                    "Week 3",
                    "Week 4",
                    "Now"
                ],

                "1Y": [
                    "Jan",
                    "Apr",
                    "Jul",
                    "Oct",
                    "Dec"
                ]

            };


            /* =====================================================
               PURITY DATA
            ===================================================== */

            const purityData = {

                "24K": {
                    price: "₹ 6,245",
                    change: "↑ 1.28%",
                    high: "₹ 6,310",
                    low: "₹ 6,150"
                },

                "22K": {
                    price: "₹ 5,725",
                    change: "↑ 1.12%",
                    high: "₹ 5,790",
                    low: "₹ 5,650"
                },

                "18K": {
                    price: "₹ 4,684",
                    change: "↑ 0.96%",
                    high: "₹ 4,735",
                    low: "₹ 4,610"
                }

            };


            /* =====================================================
               GRAPH ELEMENTS
            ===================================================== */

            const line =
                document.getElementById("chartLine");

            const fill =
                document.getElementById("chartAreaFill");

            const point =
                document.getElementById("chartPoint");


            /* =====================================================
               DRAW GRAPH
            ===================================================== */

            function drawGraph(period) {

                const data = graphData[period];

                const width = 700;
                const height = 100;

                const side = 5;
                const top = 10;
                const bottom = 9;


                const min =
                    Math.min(...data);

                const max =
                    Math.max(...data);

                const range =
                    max - min || 1;


                const points = data.map(
                    function(value, index) {

                        const x =
                            side +
                            (
                                index /
                                (data.length - 1)
                            ) *
                            (
                                width -
                                side * 2
                            );


                        const y =
                            height -
                            bottom -
                            (
                                (
                                    value - min
                                ) /
                                range
                            ) *
                            (
                                height -
                                top -
                                bottom
                            );


                        return {
                            x: x,
                            y: y,
                            value: value
                        };

                    }
                );


                let path = "";


                points.forEach(
                    function(p, index) {

                        if (index === 0) {

                            path =
                                "M " +
                                p.x +
                                " " +
                                p.y;

                        } else {

                            path +=
                                " L " +
                                p.x +
                                " " +
                                p.y;

                        }

                    }
                );


                const fillPath =
                    path +
                    " L " +
                    (width - side) +
                    " " +
                    height +
                    " L " +
                    side +
                    " " +
                    height +
                    " Z";


                line.setAttribute(
                    "d",
                    path
                );


                fill.setAttribute(
                    "d",
                    fillPath
                );


                /* =================================================
                   GRAPH HOVER
                ================================================= */

                const svg =
                    document.querySelector(".gold-chart");


                svg.onmousemove =
                    function(event) {

                        const rect =
                            svg.getBoundingClientRect();


                        const mouseX =
                            (
                                event.clientX -
                                rect.left
                            ) /
                            rect.width *
                            width;


                        let nearest =
                            points[0];


                        points.forEach(
                            function(p) {

                                if (
                                    Math.abs(
                                        p.x -
                                        mouseX
                                    ) <
                                    Math.abs(
                                        nearest.x -
                                        mouseX
                                    )
                                ) {

                                    nearest = p;

                                }

                            }
                        );


                        point.setAttribute(
                            "cx",
                            nearest.x
                        );


                        point.setAttribute(
                            "cy",
                            nearest.y
                        );


                        point.style.opacity = "1";

                    };


                svg.onmouseleave =
                    function() {

                        point.style.opacity = "0";

                    };


                /* =================================================
                   LABELS
                ================================================= */

                const labels =
                    graphLabels[period];


                document.getElementById("label1")
                    .textContent = labels[0];

                document.getElementById("label2")
                    .textContent = labels[1];

                document.getElementById("label3")
                    .textContent = labels[2];

                document.getElementById("label4")
                    .textContent = labels[3];

                document.getElementById("label5")
                    .textContent = labels[4];

            }


            /* =====================================================
               INITIAL GRAPH
            ===================================================== */

            drawGraph("1D");


            /* =====================================================
               1D / 1W / 1M / 1Y
            ===================================================== */

            document
                .querySelectorAll(".period-btn")
                .forEach(
                    function(button) {

                        button.addEventListener(
                            "click",
                            function(event) {

                                event.preventDefault();


                                document
                                    .querySelectorAll(
                                        ".period-btn"
                                    )
                                    .forEach(
                                        function(btn) {

                                            btn.classList
                                                .remove(
                                                    "active"
                                                );

                                        }
                                    );


                                this.classList.add(
                                    "active"
                                );


                                const period =
                                    this.dataset.period;


                                drawGraph(period);

                            }
                        );

                    }
                );


            /* =====================================================
               24K / 22K / 18K
            ===================================================== */

            document
                .querySelectorAll(".purity-tab")
                .forEach(
                    function(button) {

                        button.addEventListener(
                            "click",
                            function(event) {

                                event.preventDefault();


                                document
                                    .querySelectorAll(
                                        ".purity-tab"
                                    )
                                    .forEach(
                                        function(btn) {

                                            btn.classList
                                                .remove(
                                                    "active"
                                                );

                                        }
                                    );


                                this.classList.add(
                                    "active"
                                );


                                const purity =
                                    this.dataset.purity;


                                const data =
                                    purityData[purity];


                                document
                                    .getElementById(
                                        "goldPrice"
                                    )
                                    .textContent =
                                    data.price;


                                document
                                    .getElementById(
                                        "goldChange"
                                    )
                                    .textContent =
                                    data.change;


                                document
                                    .getElementById(
                                        "goldHigh"
                                    )
                                    .textContent =
                                    data.high;


                                document
                                    .getElementById(
                                        "goldLow"
                                    )
                                    .textContent =
                                    data.low;

                            }
                        );

                    }
                );

        });
    </script>
</body>

</html>
