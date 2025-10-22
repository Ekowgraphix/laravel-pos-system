<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Winniema\'s Enterprise') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons/icon-72x72.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/icons/icon-96x96.png') }}">
    
    <!-- PWA Configuration -->
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Winniema\'s Enterprise') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/icons/icon-144x144.png') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset ('customer/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset ('customer/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset ('customer/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset ('customer/css/style.css')}}" rel="stylesheet">

    <!-- {{-- custom css link --}} -->
    <link rel="stylesheet" href="{{asset ('customer/css/custom.css')}}">
    
    <!-- Professional UI Enhancements -->
    <link rel="stylesheet" href="{{asset('css/professional-animations.css')}}">
    
    <style>
        /* Enhanced Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Enhanced body */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* Enhanced selection */
        ::selection {
            background: #667eea;
            color: white;
        }
        
        ::-moz-selection {
            background: #667eea;
            color: white;
        }
        
        /* Navbar spacing fix */
        body {
            padding-top: 130px;
        }
        
        @media (max-width: 767px) {
            body {
                padding-top: 70px;
            }
        }
        
        /* Pagination Horizontal Fix */
        .pagination {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            justify-content: center !important;
            align-items: center !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 20px 0 !important;
        }
        
        .pagination li {
            display: inline-flex !important;
            margin: 0 !important;
        }
        
        .pagination .page-item {
            display: inline-flex !important;
        }
        
        .pagination .page-link {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 40px !important;
            height: 40px !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            border: 2px solid #e0e0e0 !important;
            background: white !important;
            color: #495057 !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
        }
        
        .pagination .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-color: #667eea !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-color: #667eea !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
        }
        
        .pagination .page-item.disabled .page-link {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            background: #f8f9fa !important;
        }
        
        /* Force any custom pagination to be horizontal */
        nav[role="navigation"] {
            display: flex !important;
            justify-content: center !important;
        }
        
        nav[role="navigation"] > * {
            display: flex !important;
            flex-direction: row !important;
        }
    </style>
</head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 position-fixed top-0 start-0 d-flex align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 1050;">
            <div class="pulse-loader"></div>
        </div>
        <!-- Spinner End -->


        <!-- Professional Navbar -->
        <style>
            .professional-navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1040;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
                transition: all 0.3s ease;
            }
            
            .navbar-collapse {
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                display: flex !important;
            }
            
            .navbar-collapse .d-flex {
                flex-direction: row !important;
                display: flex !important;
            }
            
            #navbarCollapse {
                flex-direction: row !important;
            }
            
            #navbarCollapse > div {
                flex-direction: row !important;
            }
            
            .professional-navbar.scrolled {
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
            }
            
            .topbar-pro {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 8px 0;
                font-size: 13px;
            }
            
            .topbar-pro a {
                color: rgba(255, 255, 255, 0.9);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .topbar-pro a:hover {
                color: white;
            }
            
            .navbar-brand-pro {
                font-size: 28px;
                font-weight: 800;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-decoration: none;
                letter-spacing: -0.5px;
                transition: transform 0.3s ease;
            }
            
            .navbar-brand-pro:hover {
                transform: scale(1.05);
            }
            
            .nav-link-pro {
                color: #212529;
                font-weight: 600;
                font-size: 15px;
                padding: 10px 20px;
                margin: 0 5px;
                border-radius: 12px;
                transition: all 0.3s ease;
                position: relative;
                letter-spacing: 0.3px;
                text-decoration: none;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
            }
            
            .nav-link-pro:hover {
                color: #667eea;
                background: rgba(102, 126, 234, 0.08);
                transform: translateY(-2px);
            }
            
            .nav-link-pro.active {
                color: white;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }
            
            .nav-icon-pro {
                position: relative;
                color: #495057;
                font-size: 22px;
                padding: 10px;
                border-radius: 12px;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .nav-icon-pro:hover {
                background: rgba(102, 126, 234, 0.08);
                color: #667eea;
                transform: translateY(-2px);
            }
            
            .cart-badge-pro {
                position: absolute;
                top: -5px;
                right: -5px;
                background: linear-gradient(135deg, #f44336 0%, #da190b 100%);
                color: white;
                border-radius: 50%;
                width: 22px;
                height: 22px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
                animation: badgePulse 2s ease-in-out infinite;
            }
            
            .logout-btn-pro {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                padding: 10px 28px;
                border-radius: 50px;
                font-weight: 700;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                letter-spacing: 0.5px;
            }
            
            .logout-btn-pro:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            }
            
            .profile-dropdown-pro {
                position: relative;
            }
            
            .profile-btn-pro {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 16px;
                border-radius: 50px;
                background: rgba(102, 126, 234, 0.08);
                border: 2px solid transparent;
                transition: all 0.3s ease;
                text-decoration: none;
                color: #212529;
            }
            
            .profile-btn-pro:hover {
                border-color: #667eea;
                background: rgba(102, 126, 234, 0.12);
                transform: translateY(-2px);
                color: #667eea;
            }
            
            .profile-avatar-pro {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 16px;
                font-weight: 700;
                overflow: hidden;
                border: 2px solid white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .profile-avatar-pro img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            
            .profile-avatar-fallback {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .profile-name-pro {
                font-weight: 700;
                font-size: 14px;
                letter-spacing: 0.3px;
            }
            
            .dropdown-menu-pro {
                border: none;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                padding: 8px;
                min-width: 200px;
                margin-top: 8px;
                animation: dropdownSlide 0.3s ease;
            }
            
            .dropdown-item-pro {
                border-radius: 10px;
                padding: 12px 16px;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.2s ease;
                color: #495057;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .dropdown-item-pro:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                color: #667eea;
                transform: translateX(5px);
            }
            
            .mobile-menu-icon {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 10px 14px;
                border-radius: 12px;
                color: white;
                font-size: 20px;
                transition: transform 0.3s ease;
            }
            
            .mobile-menu-icon:hover {
                transform: rotate(90deg);
            }
            
            @keyframes dropdownSlide {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @media (max-width: 1199px) {
                .navbar-collapse {
                    background: white;
                    border-radius: 16px;
                    margin-top: 15px;
                    padding: 20px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                    flex-direction: column !important;
                    align-items: stretch !important;
                }
                
                .navbar-collapse .d-flex {
                    flex-direction: row !important;
                    flex-wrap: wrap !important;
                    justify-content: center !important;
                }
                
                .nav-link-pro {
                    margin: 5px;
                    text-align: center;
                    font-size: 14px;
                    padding: 10px 16px;
                }
            }
            
            @media (max-width: 576px) {
                .nav-link-pro {
                    font-size: 13px;
                    padding: 8px 12px;
                }
                
                .nav-link-pro i {
                    font-size: 14px;
                }
            }
        </style>
        
        <!-- Top Bar -->
        <div class="topbar-pro d-none d-md-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex gap-4">
                            <a href="#" class="d-flex align-items-center gap-2">
                                <i class="fas fa-phone-alt"></i>
                                <span>+1 234 567 8900</span>
                            </a>
                            <a href="#" class="d-flex align-items-center gap-2">
                                <i class="fas fa-envelope"></i>
                                <span>support@yourstore.com</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex gap-3 justify-content-end align-items-center">
                            <a href="#" class="d-flex align-items-center gap-2">
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure Checkout</span>
                            </a>
                            <a href="#" class="d-flex align-items-center gap-2">
                                <i class="fas fa-truck"></i>
                                <span>Free Shipping</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Navbar -->
        <nav class="professional-navbar" id="mainNavbar">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <!-- Logo -->
                    <a href="{{route('userDashboard')}}" class="navbar-brand-pro">
                        <i class="fas fa-shopping-bag me-2"></i>{{ config('app.name', 'Winniema\'s Enterprise') }}
                    </a>
                    
                    <!-- Mobile Toggle -->
                    <button class="mobile-menu-icon d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Desktop Navigation -->
                    <div class="collapse navbar-collapse" id="navbarCollapse" style="flex-direction: row;">
                        <!-- Nav Links -->
                        <div class="d-flex flex-row flex-wrap align-items-center justify-content-center gap-2 mx-auto mt-3 mt-xl-0" style="flex-direction: row !important;">
                            <a href="{{route('userDashboard')}}" class="nav-link-pro {{ request()->routeIs('userDashboard') ? 'active' : '' }}">
                                <i class="fas fa-home me-2"></i>Home
                            </a>
                            <a href="{{route('shopList')}}" class="nav-link-pro {{ request()->routeIs('shopList') ? 'active' : '' }}">
                                <i class="fas fa-store me-2"></i>Shop
                            </a>
                            <a href="{{route('orderList')}}" class="nav-link-pro {{ request()->routeIs('orderList') || request()->routeIs('customerOrders') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list me-2"></i>My Orders
                            </a>
                            <a href="{{route('contactUs')}}" class="nav-link-pro {{ request()->routeIs('contactUs') ? 'active' : '' }}">
                                <i class="fas fa-envelope me-2"></i>Contact
                            </a>
                        </div>
                        
                        <!-- Right Side Icons -->
                        <div class="d-flex flex-row align-items-center justify-content-center gap-3 mt-3 mt-xl-0" style="flex-direction: row !important;">
                            <!-- Cart -->
                            <a href="{{route('cart')}}" class="nav-icon-pro position-relative" title="Shopping Cart">
                                <i class="fas fa-shopping-cart"></i>
                                @if($cartCount > 0)
                                    <span class="cart-badge-pro">{{ $cartCount }}</span>
                                @endif
                            </a>
                            
                            <!-- Profile Dropdown -->
                            <div class="dropdown profile-dropdown-pro">
                                <a href="#" class="profile-btn-pro dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-avatar-pro">
                                        @if(auth()->user()->profile)
                                            <img src="{{ asset('userProfile/' . auth()->user()->profile) }}" alt="Profile">
                                        @else
                                            <div class="profile-avatar-fallback">
                                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nickname, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="profile-name-pro d-none d-lg-block">
                                        {{ auth()->user()->name ?? auth()->user()->nickname }}
                                    </span>
                                    <i class="fas fa-chevron-down d-none d-lg-block" style="font-size: 12px;"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-pro dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item-pro" href="{{route('userProfileDetails')}}">
                                            <i class="fas fa-user-circle"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-pro" href="{{route('orderList')}}">
                                            <i class="fas fa-shopping-bag"></i> My Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-pro" href="{{route('changePassword')}}">
                                            <i class="fas fa-key"></i> Change Password
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item-pro text-danger">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <script>
            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('mainNavbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Active link highlighting
            document.querySelectorAll('.nav-link-pro').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.nav-link-pro').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        </script>

        @yield('content')

    <!-- Professional Footer -->
    <style>
        .footer-pro {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 70px 0 0;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .footer-pro::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }
        
        .footer-brand-pro {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .footer-tagline {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
            margin: 20px 0;
        }
        
        .newsletter-pro {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .newsletter-title {
            color: white;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .newsletter-text {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 25px;
        }
        
        .newsletter-input-group {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .newsletter-input {
            width: 100%;
            padding: 16px 160px 16px 24px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.08);
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .newsletter-input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.12);
        }
        
        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .newsletter-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .newsletter-btn:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
        
        .footer-heading {
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 12px;
        }
        
        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .footer-link-pro {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .footer-link-pro:hover {
            color: #667eea;
            transform: translateX(5px);
        }
        
        .footer-link-pro i {
            font-size: 12px;
            opacity: 0.7;
        }
        
        .social-links-pro {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        
        .social-link-pro {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .social-link-pro:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .contact-item-pro {
            display: flex;
            align-items: start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .contact-icon-pro {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(102, 126, 234, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .contact-text-pro {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.6;
        }
        
        .copyright-pro {
            background: rgba(0, 0, 0, 0.3);
            padding: 25px 0;
            margin-top: 60px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .copyright-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }
        
        .copyright-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .copyright-link:hover {
            color: #764ba2;
        }
        
        @media (max-width: 768px) {
            .footer-pro {
                padding: 50px 0 0;
            }
            
            .newsletter-pro {
                padding: 30px 20px;
            }
            
            .newsletter-input {
                padding: 14px 20px;
                margin-bottom: 15px;
            }
            
            .newsletter-btn {
                position: static;
                transform: none;
                width: 100%;
            }
        }
    </style>
    
    <footer class="footer-pro">
        <div class="container">
            <!-- Newsletter Section -->
            <div class="newsletter-pro text-center">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-lg-start mb-4 mb-lg-0">
                        <h3 class="newsletter-title">Stay Updated!</h3>
                        <p class="newsletter-text mb-0">Subscribe to get special offers, free giveaways, and exclusive deals.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-input-group">
                            <input type="email" class="newsletter-input" placeholder="Enter your email address">
                            <button type="submit" class="newsletter-btn">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Main Footer Content -->
            <div class="row g-5 mt-5">
                <!-- Brand Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-brand-pro">
                        <i class="fas fa-shopping-bag me-2"></i>Modern POS
                    </div>
                    <p class="footer-tagline">Since 2025</p>
                    <p class="footer-description">
                        Your trusted partner for quality products and exceptional service. Experience shopping redefined.
                    </p>
                    <div class="social-links-pro">
                        <a href="https://www.facebook.com/joincoder404" class="social-link-pro" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.youtube.com/@joincoder" class="social-link-pro" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://www.tiktok.com/@sourcecode" class="social-link-pro" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://t.me/reansourcecode" class="social-link-pro" title="Telegram">
                            <i class="fab fa-telegram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-heading">Quick Links</h4>
                    <a href="{{route('userDashboard')}}" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Home
                    </a>
                    <a href="{{route('shopList')}}" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Shop
                    </a>
                    <a href="{{route('orderList')}}" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> My Orders
                    </a>
                    <a href="{{route('contactUs')}}" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Contact Us
                    </a>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> About Us
                    </a>
                </div>
                
                <!-- Customer Service -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-heading">Customer Service</h4>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Track Order
                    </a>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Returns & Exchanges
                    </a>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Shipping Info
                    </a>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> FAQ
                    </a>
                    <a href="#" class="footer-link-pro">
                        <i class="fas fa-chevron-right"></i> Privacy Policy
                    </a>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-heading">Get In Touch</h4>
                    <div class="contact-item-pro">
                        <div class="contact-icon-pro">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text-pro">
                            123 Business Street,<br>New York, NY 10001
                        </div>
                    </div>
                    <div class="contact-item-pro">
                        <div class="contact-icon-pro">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text-pro">
                            support@modernpos.com
                        </div>
                    </div>
                    <div class="contact-item-pro">
                        <div class="contact-icon-pro">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text-pro">
                            +1 (234) 567-8900
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="copyright-pro">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="copyright-text mb-0">
                            <i class="fas fa-copyright me-2"></i>{{ date('Y') }} Modern POS. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <p class="copyright-text mb-0">
                            Made with <i class="fas fa-heart text-danger"></i> by <a href="#" class="copyright-link">Your Team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Copyright End -->

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{asset ('customer/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset ('customer/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset ('customer/lib/lightbox/js/lightbox.min.js')}}"></script>
    <script src="{{asset ('customer/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset ('customer/js/main.js')}}"></script>

    <script>
        function loadFile(event) {
            var reader = new FileReader();

            reader.onload = function() {
                var output = document.getElementById('output');

                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0])
        }


    </script>
    
    <!-- Professional UI Enhancements JavaScript -->
    <script src="{{asset('js/professional-ui.js')}}"></script>
    
    <!-- Success/Error Message Display -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification === 'function') {
                showNotification("{{ session('success') }}", 'success');
            }
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification === 'function') {
                showNotification("{{ session('error') }}", 'error');
            }
        });
    </script>
    @endif
    
    @if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification === 'function') {
                showNotification("{{ session('info') }}", 'info');
            }
        });
    </script>
    @endif
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
    
    </body>

    @yield('js-section')

</html>
