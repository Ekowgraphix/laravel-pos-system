<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', 'Winniema\'s Enterprise') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/icon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icons/icon-72x72.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/icons/icon-96x96.png') }}">

    <!-- Custom fonts for this template-->
    <link href="{{asset ('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template-->
        <link href="{{asset ('admin/css/sb-admin-2.css')}}" rel="stylesheet">
        <link href="{{asset ('admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
        
    <style>
        /* CSS Variables for Adaptive Styling */
        :root {
            --overlay-opacity: 0.3;
            --card-opacity: 0.95;
            --card-blur: 30px;
            --accent-color: #667eea;
            --accent-secondary: #764ba2;
        }
        
        /* Apply adaptive overlay */
        .auth-body::before {
            opacity: var(--overlay-opacity) !important;
        }
        
        /* Background Selector Styles */
        .background-selector {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            animation: fadeInUp 0.5s ease;
        }
        
        .bg-selector-trigger {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .bg-selector-trigger:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        }
        
        .bg-selector-trigger:active {
            transform: translateY(-2px);
        }
        
        .bg-options {
            position: absolute;
            bottom: 80px;
            right: 0;
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: none;
            min-width: 300px;
            animation: slideUp 0.3s ease;
        }
        
        .bg-options.active {
            display: block;
        }
        
        .bg-options-title {
            font-size: 16px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .bg-thumbnails {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .bg-thumbnail {
            width: 100%;
            height: 100px;
            border-radius: 12px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            background-size: cover;
            background-position: center;
        }
        
        .bg-thumbnail:hover {
            transform: scale(1.05);
            border-color: #667eea;
        }
        
        .bg-thumbnail.active {
            border-color: #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .bg-thumbnail.active::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 5px;
            background: #667eea;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }
        
        .auth-body {
            min-height: 100vh;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            position: relative;
        }
        
        .auth-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }
        
        .container {
            position: relative;
            z-index: 1;
        }
        
        .loginform, .registerform {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .bg-label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-align: center;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 768px) {
            .background-selector {
                bottom: 20px;
                right: 20px;
            }
            
            .bg-selector-trigger {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .bg-options {
                min-width: 250px;
                padding: 15px;
            }
            
            .bg-thumbnail {
                height: 80px;
            }
        }
    </style>

</head>

<body class="auth-body" id="authBody" style="background: linear-gradient(135deg, darkslategray, #2a9877); transition: background 0.5s ease;">

    @yield('content')
    
    <!-- Background Selector -->
    <div class="background-selector">
        <button class="bg-selector-trigger" id="bgTrigger" title="Change Background">
            <i class="fas fa-palette"></i>
        </button>
        
        <div class="bg-options" id="bgOptions">
            <div class="bg-options-title">
                <i class="fas fa-image"></i>
                Choose Background
            </div>
            <div class="bg-thumbnails">
                <div class="bg-thumbnail" data-bg="bg1" data-mode="dark" style="background-image: url('{{ asset('auth-backgrounds/bg1.jpg') }}');">
                    <div class="bg-label">Platinum Card</div>
                </div>
                <div class="bg-thumbnail" data-bg="bg2" data-mode="light" style="background-image: url('{{ asset('auth-backgrounds/bg2.jpg') }}');">
                    <div class="bg-label">POS Terminal</div>
                </div>
                <div class="bg-thumbnail" data-bg="bg3" data-mode="dark" style="background-image: url('{{ asset('auth-backgrounds/bg3.jpg') }}');">
                    <div class="bg-label">Glow Card</div>
                </div>
                <div class="bg-thumbnail" data-bg="bg4" data-mode="light-pink" style="background-image: url('{{ asset('auth-backgrounds/bg4.jpg') }}');">
                    <div class="bg-label">Mobile Pay</div>
                </div>
                <div class="bg-thumbnail" data-bg="bg5" data-mode="dark-purple" style="background-image: url('{{ asset('auth-backgrounds/bg5.jpg') }}');">
                    <div class="bg-label">Purple Card</div>
                </div>
                <div class="bg-thumbnail" data-bg="gradient" data-mode="dark" style="background: linear-gradient(135deg, darkslategray, #2a9877);">
                    <div class="bg-label">Default</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Background Selector JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('bgTrigger');
            const options = document.getElementById('bgOptions');
            const body = document.getElementById('authBody');
            const thumbnails = document.querySelectorAll('.bg-thumbnail');
            
            // Load saved background and mode
            const savedBg = localStorage.getItem('authBackground');
            const savedMode = localStorage.getItem('authBgMode') || 'dark';
            if (savedBg) {
                applyBackground(savedBg, savedMode);
                updateActiveState(savedBg);
            } else {
                applyBackground('gradient', 'dark');
                updateActiveState('gradient');
            }
            
            // Toggle options
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                options.classList.toggle('active');
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!options.contains(e.target) && e.target !== trigger) {
                    options.classList.remove('active');
                }
            });
            
            // Handle thumbnail clicks
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const bgType = this.getAttribute('data-bg');
                    const bgMode = this.getAttribute('data-mode');
                    applyBackground(bgType, bgMode);
                    updateActiveState(bgType);
                    localStorage.setItem('authBackground', bgType);
                    localStorage.setItem('authBgMode', bgMode);
                    
                    // Show feedback
                    trigger.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        trigger.innerHTML = '<i class="fas fa-palette"></i>';
                    }, 1000);
                });
            });
            
            function applyBackground(bgType, bgMode) {
                // Apply background image
                if (bgType === 'gradient') {
                    body.style.background = 'linear-gradient(135deg, darkslategray, #2a9877)';
                    body.style.backgroundSize = 'cover';
                } else {
                    const bgUrl = '{{ asset("auth-backgrounds") }}/' + bgType + '.jpg';
                    body.style.background = `url('${bgUrl}') center/cover fixed`;
                }
                
                // Apply adaptive styles based on mode
                applyAdaptiveStyles(bgMode);
            }
            
            function applyAdaptiveStyles(mode) {
                const root = document.documentElement;
                
                // Remove all mode classes
                root.classList.remove('bg-dark', 'bg-light', 'bg-light-pink', 'bg-dark-purple');
                
                // Add current mode class
                if (mode) {
                    root.classList.add('bg-' + mode);
                }
                
                // Apply mode-specific variables
                switch(mode) {
                    case 'dark':
                        root.style.setProperty('--overlay-opacity', '0.4');
                        root.style.setProperty('--card-opacity', '0.98');
                        root.style.setProperty('--card-blur', '35px');
                        root.style.setProperty('--accent-color', '#667eea');
                        root.style.setProperty('--accent-secondary', '#764ba2');
                        break;
                    case 'light':
                        root.style.setProperty('--overlay-opacity', '0.15');
                        root.style.setProperty('--card-opacity', '0.92');
                        root.style.setProperty('--card-blur', '25px');
                        root.style.setProperty('--accent-color', '#667eea');
                        root.style.setProperty('--accent-secondary', '#764ba2');
                        break;
                    case 'light-pink':
                        root.style.setProperty('--overlay-opacity', '0.2');
                        root.style.setProperty('--card-opacity', '0.95');
                        root.style.setProperty('--card-blur', '30px');
                        root.style.setProperty('--accent-color', '#e91e63');
                        root.style.setProperty('--accent-secondary', '#9c27b0');
                        break;
                    case 'dark-purple':
                        root.style.setProperty('--overlay-opacity', '0.5');
                        root.style.setProperty('--card-opacity', '0.96');
                        root.style.setProperty('--card-blur', '32px');
                        root.style.setProperty('--accent-color', '#9c27b0');
                        root.style.setProperty('--accent-secondary', '#673ab7');
                        break;
                    default:
                        root.style.setProperty('--overlay-opacity', '0.3');
                        root.style.setProperty('--card-opacity', '0.95');
                        root.style.setProperty('--card-blur', '30px');
                        root.style.setProperty('--accent-color', '#667eea');
                        root.style.setProperty('--accent-secondary', '#764ba2');
                }
            }
            
            function updateActiveState(bgType) {
                thumbnails.forEach(thumb => {
                    if (thumb.getAttribute('data-bg') === bgType) {
                        thumb.classList.add('active');
                    } else {
                        thumb.classList.remove('active');
                    }
                });
            }
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset ('admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset ('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset ('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset ('admin/js/sb-admin-2.min.js')}}"></script>

</body>

</html>
