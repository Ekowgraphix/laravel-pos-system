<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', 'Winniema\'s Enterprise') }} - Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons/icon-72x72.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/icons/icon-96x96.png') }}">

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> --}}

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/sb-admin-2.css') }}" rel="stylesheet">
    
    <!-- Modern Professional Admin Styles -->
    <link href="{{ asset('admin/css/modern-admin.css') }}" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <button id="sidebarToggle" class="btn bg-dark d-lg-none">
                <i class="fa fa-bars"></i>
            </button>
        <!-- Modern Sidebar -->
        <ul class="navbar-nav sidebar sidebar-modern" id="accordionSidebar">
            <!-- Sidebar Brand -->
            <a class="sidebar-brand-modern" href="{{ route('adminDashboard') }}">
                <div class="sidebar-brand-icon-modern">
                    <img alt="Logo" src="{{ asset('adminProfile/laravel.png') }}">
                </div>
                <span class="sidebar-brand-text-modern">{{ config('app.name', 'Winniema\'s Enterprise') }}</span>
            </a>
            
            <!-- Sidebar Navigation -->
            <div class="sidebar-nav-modern">
                <!-- Dashboard -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('adminDashboard') }}" class="sidebar-nav-link-modern {{ request()->routeIs('adminDashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Categories -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('categoryList') }}" class="sidebar-nav-link-modern {{ request()->routeIs('categoryList') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <!-- Products -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('productList') }}" class="sidebar-nav-link-modern {{ request()->routeIs('productList') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>

                <!-- Orders -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('orderListPage') }}" class="sidebar-nav-link-modern {{ request()->routeIs('orderListPage') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Sales -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('saleInfoList') }}" class="sidebar-nav-link-modern {{ request()->routeIs('saleInfoList') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Sales</span>
                    </a>
                </li>

                <!-- Payments -->
                <li class="sidebar-nav-item-modern">
                    <a href="{{ route('paymentList') }}" class="sidebar-nav-link-modern {{ request()->routeIs('paymentList') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Payments</span>
                    </a>
                </li>


                @if (auth()->user()->role == 'superadmin')
                <!-- Manage Users (with submenu) -->
                <li class="sidebar-nav-item-modern">
                    <a href="#" class="sidebar-nav-link-modern has-submenu toggle-submenu">
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Users</span>
                    </a>
                    <ul class="sidebar-submenu-modern">
                        <li>
                            <a href="{{ route('createAdminAccount') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-user-plus"></i>
                                <span>Add New Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('resetPasswordPage') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-key"></i>
                                <span>Reset Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('adminList') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-user-tie"></i>
                                <span>Admin Profiles</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Reports (with submenu) -->
                <li class="sidebar-nav-item-modern">
                    <a href="#" class="sidebar-nav-link-modern has-submenu toggle-submenu">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="sidebar-submenu-modern">
                        <li>
                            <a href="{{ route('salesReportPage') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Sales Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('productReportPage') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-box-open"></i>
                                <span>Products Info</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profitlossreportpage') }}" class="sidebar-submenu-link-modern">
                                <i class="fas fa-chart-line"></i>
                                <span>Profit & Loss</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider-modern">

                <!-- Logout -->
                <li class="sidebar-nav-item-modern">
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="sidebar-logout-modern">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" style="background-color: #f8f9fc;">
            <!-- Main Content -->
            <div id="content">
                <!-- Modern Topbar -->
                <nav class="topbar-modern">
                    <div class="topbar-content">
                        <!-- Left Section: Sidebar Toggle & Breadcrumb -->
                        <div class="topbar-left">
                            <button class="sidebar-toggle-btn" id="sidebarToggle">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="breadcrumb-modern">
                                <i class="fas fa-home"></i>
                                <span>{{ ucfirst(Request::segment(1) ?? 'Home') }}</span>
                                @if(Request::segment(2))
                                    <i class="fas fa-chevron-right"></i>
                                    <span>{{ ucfirst(Request::segment(2)) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Right Section: User Profile -->
                        <div class="topbar-right">
                            <!-- User Dropdown -->
                            <div class="user-profile-dropdown">
                                <button class="user-profile-btn" id="userDropdownBtn">
                                    <div class="user-info">
                                        <span class="user-name">
                                            @if (auth()->user()->name != null)
                                                {{ auth()->user()->name }}
                                            @else
                                                {{ auth()->user()->nickname }}
                                            @endif
                                        </span>
                                        <span class="user-role">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    </div>
                                    <img class="user-avatar" 
                                         src="{{ auth()->user()->profile ? asset('adminProfile/' . auth()->user()->profile) : asset('admin/img/undraw_profile.svg') }}"
                                         alt="User Avatar">
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </button>

                                <div class="user-dropdown-menu" id="userDropdownMenu">
                                    <div class="dropdown-header">
                                        <img class="dropdown-avatar" 
                                             src="{{ auth()->user()->profile ? asset('adminProfile/' . auth()->user()->profile) : asset('admin/img/undraw_profile.svg') }}"
                                             alt="User Avatar">
                                        <div class="dropdown-user-info">
                                            <p class="dropdown-user-name">
                                                @if (auth()->user()->name != null)
                                                    {{ auth()->user()->name }}
                                                @else
                                                    {{ auth()->user()->nickname }}
                                                @endif
                                            </p>
                                            <p class="dropdown-user-email">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('profileDetails') }}">
                                        <i class="fas fa-user"></i>
                                        <span>My Profile</span>
                                    </a>
                                    @if (auth()->user()->role == 'superadmin')
                                        <a class="dropdown-item" href="{{ route('createAdminAccount') }}">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Add Admin</span>
                                        </a>
                                    @endif
                                    @if (auth()->user()->provider == 'simple')
                                        <a class="dropdown-item" href="{{ route('passwordChange') }}">
                                            <i class="fas fa-key"></i>
                                            <span>Change Password</span>
                                        </a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="dropdown-item logout-item">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- End of Topbar -->

                @yield('content')
                @include('sweetalert::alert')

            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#sidebarToggle").click(function () {
                $(".sidebar").toggleClass("active");
            });

            // Ensure clicking outside closes sidebar
            $(document).click(function (event) {
                if (!$(event.target).closest(".sidebar, #sidebarToggle").length) {
                    $(".sidebar").removeClass("active");
                }
            });

            // Modern Sidebar Submenu Toggle
            $(".toggle-submenu").click(function(e) {
                e.preventDefault();
                $(this).toggleClass("open");
                $(this).next(".sidebar-submenu-modern").toggleClass("open");
            });

            // User Dropdown Toggle
            $("#userDropdownBtn").click(function(e) {
                e.stopPropagation();
                $("#userDropdownMenu").toggleClass("show");
                $(".dropdown-arrow").toggleClass("rotate");
            });

            // Close dropdown when clicking outside
            $(document).click(function(event) {
                if (!$(event.target).closest(".user-profile-dropdown").length) {
                    $("#userDropdownMenu").removeClass("show");
                    $(".dropdown-arrow").removeClass("rotate");
                }
            });
        });

        function loadFile(event) {
            var reader = new FileReader();

            reader.onload = function() {
                var output = document.getElementById('output');

                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0])
        }

           // Toggle submenu Reports
            $(document).ready(function(){
            $(".toggle-submenu").click(function(e){
                e.preventDefault(); // Prevent default link action
                $(this).next(".submenu").slideToggle();
            });
        });
    </script>

</body>

@yield('js-section')

</html>
