@extends('Authentication.layouts.master')

@section('content')
<style>
    /* Particle Background */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        pointer-events: none;
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(2px);
        box-shadow: 
            0 0 20px currentColor,
            0 0 40px currentColor,
            0 0 60px currentColor;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.1); }
    }
    
    /* Auth Container */
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        position: relative;
    }
    
    .register-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(40px) saturate(200%) brightness(1.1);
        -webkit-backdrop-filter: blur(40px) saturate(200%) brightness(1.1);
        border-radius: 35px;
        box-shadow: 
            0 8px 32px 0 rgba(102, 126, 234, 0.37),
            0 0 60px rgba(102, 126, 234, 0.3),
            0 0 100px rgba(118, 75, 162, 0.2),
            inset 0 0 80px rgba(255, 255, 255, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        overflow: visible;
        max-width: 580px;
        width: 100%;
        border: 2px solid rgba(255, 255, 255, 0.18);
        animation: slideUpScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1), neonPulse 3s ease-in-out infinite;
        position: relative;
        transition: all 0.5s ease;
    }
    
    @keyframes neonPulse {
        0%, 100% { 
            box-shadow: 
                0 8px 32px 0 rgba(102, 126, 234, 0.37),
                0 0 60px rgba(102, 126, 234, 0.3),
                0 0 100px rgba(118, 75, 162, 0.2),
                inset 0 0 80px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        50% { 
            box-shadow: 
                0 8px 32px 0 rgba(102, 126, 234, 0.5),
                0 0 80px rgba(102, 126, 234, 0.5),
                0 0 120px rgba(118, 75, 162, 0.4),
                inset 0 0 100px rgba(255, 255, 255, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }
    }
    
    .register-card::before {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        background: linear-gradient(135deg, var(--accent-color, #667eea), var(--accent-secondary, #764ba2), var(--accent-color, #667eea));
        border-radius: 35px;
        opacity: 0.4;
        filter: blur(20px);
        transition: opacity 0.5s ease;
        z-index: -1;
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite, neonGlow 2s ease-in-out infinite alternate;
    }
    
    @keyframes neonGlow {
        0% { opacity: 0.4; filter: blur(20px); }
        100% { opacity: 0.7; filter: blur(25px); }
    }
    
    .register-card:hover::before {
        opacity: 0.8;
        filter: blur(30px);
    }
    
    @keyframes slideUpScale {
        0% {
            opacity: 0;
            transform: translateY(50px) scale(0.9);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .register-header {
        text-align: center;
        padding: 50px 40px 30px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }
    
    .register-logo {
        width: 90px;
        height: 90px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, var(--accent-color, #667eea) 0%, var(--accent-secondary, #764ba2) 100%);
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 
            0 0 40px rgba(102, 126, 234, 0.8),
            0 0 80px rgba(102, 126, 234, 0.5),
            0 0 120px rgba(118, 75, 162, 0.3),
            inset 0 0 20px rgba(255, 255, 255, 0.3),
            inset 0 -2px 10px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: logoFloat 3s ease-in-out infinite, logoNeonPulse 2s ease-in-out infinite;
        transition: all 0.5s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    @keyframes logoNeonPulse {
        0%, 100% {
            box-shadow: 
                0 0 40px rgba(102, 126, 234, 0.8),
                0 0 80px rgba(102, 126, 234, 0.5),
                0 0 120px rgba(118, 75, 162, 0.3),
                inset 0 0 20px rgba(255, 255, 255, 0.3);
        }
        50% {
            box-shadow: 
                0 0 60px rgba(102, 126, 234, 1),
                0 0 100px rgba(102, 126, 234, 0.7),
                0 0 140px rgba(118, 75, 162, 0.5),
                inset 0 0 30px rgba(255, 255, 255, 0.5);
        }
    }
    
    .register-logo:hover {
        transform: scale(1.05) rotate(5deg);
    }
    
    .register-logo::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 28px;
        opacity: 0.3;
        filter: blur(10px);
        z-index: -1;
    }
    
    .register-logo i {
        font-size: 45px;
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        animation: iconBounce 2s ease-in-out infinite;
    }
    
    @keyframes logoFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    @keyframes iconBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .register-title {
        font-size: 32px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
        text-shadow: 
            0 0 20px rgba(102, 126, 234, 0.8),
            0 0 40px rgba(102, 126, 234, 0.5),
            0 0 60px rgba(118, 75, 162, 0.3);
        animation: textNeonGlow 3s ease-in-out infinite;
    }
    
    @keyframes textNeonGlow {
        0%, 100% {
            text-shadow: 
                0 0 20px rgba(102, 126, 234, 0.8),
                0 0 40px rgba(102, 126, 234, 0.5),
                0 0 60px rgba(118, 75, 162, 0.3);
        }
        50% {
            text-shadow: 
                0 0 30px rgba(102, 126, 234, 1),
                0 0 60px rgba(102, 126, 234, 0.7),
                0 0 90px rgba(118, 75, 162, 0.5);
        }
    }
    
    .register-subtitle {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        text-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
    }
    
    .register-body {
        padding: 40px;
    }
    
    .form-group-modern {
        margin-bottom: 22px;
        position: relative;
    }
    
    .form-label-modern {
        position: absolute;
        left: 50px;
        top: 17px;
        font-size: 15px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(to bottom, transparent 50%, rgba(255, 255, 255, 0.05) 50%);
        padding: 0 5px;
        z-index: 3;
        text-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
    }
    
    .form-input-modern:focus ~ .form-label-modern,
    .form-input-modern:not(:placeholder-shown) ~ .form-label-modern {
        top: -10px;
        left: 45px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
        text-shadow: 0 0 10px var(--accent-color, #667eea);
        background: linear-gradient(to bottom, transparent 50%, rgba(102, 126, 234, 0.1) 50%);
    }
    
    .input-icon-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(102, 126, 234, 0.8);
        font-size: 18px;
        z-index: 2;
        transition: all 0.3s ease;
        filter: drop-shadow(0 0 5px rgba(102, 126, 234, 0.5));
    }
    
    .input-icon-wrapper:focus-within .input-icon {
        color: var(--accent-color, #667eea);
        filter: drop-shadow(0 0 10px var(--accent-color, #667eea));
        transform: translateY(-50%) scale(1.1);
    }
    
    .form-input-modern {
        width: 100%;
        padding: 16px 50px 16px 50px;
        border: 2px solid rgba(102, 126, 234, 0.3);
        border-radius: 16px;
        font-size: 15px;
        font-weight: 500;
        color: #212529;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        box-shadow: 
            0 2px 8px rgba(0, 0, 0, 0.04),
            0 0 20px rgba(102, 126, 234, 0.1),
            inset 0 1px 2px rgba(255, 255, 255, 0.5);
    }
    
    .form-input-modern:focus {
        outline: none;
        border-color: var(--accent-color, #667eea);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        box-shadow: 
            0 0 30px rgba(102, 126, 234, 0.6),
            0 0 60px rgba(102, 126, 234, 0.4),
            0 0 100px rgba(118, 75, 162, 0.2),
            inset 0 0 20px rgba(102, 126, 234, 0.1),
            inset 0 1px 3px rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
        animation: inputNeonGlow 1.5s ease-in-out infinite;
    }
    
    @keyframes inputNeonGlow {
        0%, 100% {
            box-shadow: 
                0 0 30px rgba(102, 126, 234, 0.6),
                0 0 60px rgba(102, 126, 234, 0.4),
                0 0 100px rgba(118, 75, 162, 0.2),
                inset 0 0 20px rgba(102, 126, 234, 0.1);
        }
        50% {
            box-shadow: 
                0 0 40px rgba(102, 126, 234, 0.8),
                0 0 80px rgba(102, 126, 234, 0.6),
                0 0 120px rgba(118, 75, 162, 0.4),
                inset 0 0 30px rgba(102, 126, 234, 0.15);
        }
    }
    
    .form-input-modern::placeholder {
        color: transparent;
        font-weight: 400;
    }
    
    .form-input-modern.is-invalid {
        border-color: #dc3545;
        background: #fff5f5;
        animation: shake 0.4s ease;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .password-toggle {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        z-index: 4;
        padding: 5px;
    }
    
    .password-toggle:hover {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #dc3545;
        font-weight: 600;
    }
    
    .password-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .btn-register-modern {
        width: 100%;
        padding: 17px;
        background: linear-gradient(135deg, var(--accent-color, #667eea) 0%, var(--accent-secondary, #764ba2) 100%);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 
            0 0 40px rgba(102, 126, 234, 0.8),
            0 0 80px rgba(102, 126, 234, 0.5),
            0 0 120px rgba(118, 75, 162, 0.3),
            inset 0 0 30px rgba(255, 255, 255, 0.2);
        margin-top: 15px;
        position: relative;
        overflow: hidden;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        animation: buttonNeonPulse 2s ease-in-out infinite;
    }
    
    @keyframes buttonNeonPulse {
        0%, 100% {
            box-shadow: 
                0 0 40px rgba(102, 126, 234, 0.8),
                0 0 80px rgba(102, 126, 234, 0.5),
                0 0 120px rgba(118, 75, 162, 0.3),
                inset 0 0 30px rgba(255, 255, 255, 0.2);
        }
        50% {
            box-shadow: 
                0 0 60px rgba(102, 126, 234, 1),
                0 0 100px rgba(102, 126, 234, 0.7),
                0 0 140px rgba(118, 75, 162, 0.5),
                inset 0 0 40px rgba(255, 255, 255, 0.3);
        }
    }
    
    .btn-register-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-register-modern:hover::before {
        left: 100%;
    }
    
    .btn-register-modern:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 
            0 15px 40px rgba(102, 126, 234, 0.5),
            0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-register-modern:active {
        transform: translateY(-2px) scale(1);
    }
    
    .btn-register-modern:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
        margin-left: 10px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .auth-footer {
        text-align: center;
        padding: 30px 40px;
        background: rgba(102, 126, 234, 0.05);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    .auth-link {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
        text-decoration: none;
        font-size: 15px;
        transition: all 0.3s ease;
        text-shadow: 0 0 10px rgba(102, 126, 234, 0.6);
        position: relative;
    }
    
    .auth-link:hover {
        color: #fff;
        text-shadow: 0 0 20px rgba(102, 126, 234, 1);
        text-decoration: none;
    }
    
    .auth-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--accent-color, #667eea), var(--accent-secondary, #764ba2));
        box-shadow: 0 0 10px var(--accent-color, #667eea);
        transition: width 0.3s ease;
    }
    
    .auth-link:hover::after {
        width: 100%;
    }
    
    .auth-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 15px;
        text-shadow: 0 0 8px rgba(102, 126, 234, 0.4);
    }
    
    @media (max-width: 576px) {
        .register-header {
            padding: 40px 30px 25px;
        }
        
        .register-body {
            padding: 30px 25px;
        }
        
        .register-title {
            font-size: 26px;
        }
        
        .password-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .auth-footer {
            padding: 25px 30px;
        }
    }
</style>

<!-- Particle Background -->
<div class="particles" id="particles"></div>

<div class="auth-container">
    <div class="register-card">
        <!-- Header -->
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="register-title">Create Account</h1>
            <p class="register-subtitle">Join us today and start shopping</p>
        </div>
        
        <!-- Body -->
        <div class="register-body">
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <!-- Name -->
                <div class="form-group-modern">
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" 
                               name="name" 
                               id="name"
                               class="form-input-modern @error('name') is-invalid @enderror" 
                               placeholder=" " 
                               value="{{ old('name') }}" 
                               required
                               autocomplete="name">
                        <label class="form-label-modern" for="name">Full Name</label>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="form-group-modern">
                    <div class="input-icon-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" 
                               name="email" 
                               id="email"
                               class="form-input-modern @error('email') is-invalid @enderror" 
                               placeholder=" " 
                               value="{{ old('email') }}" 
                               required
                               autocomplete="email">
                        <label class="form-label-modern" for="email">Email Address</label>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div class="form-group-modern">
                    <div class="input-icon-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" 
                               name="phone" 
                               id="phone"
                               class="form-input-modern @error('phone') is-invalid @enderror" 
                               placeholder=" " 
                               value="{{ old('phone') }}" 
                               required
                               autocomplete="tel">
                        <label class="form-label-modern" for="phone">Phone Number</label>
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Address -->
                <div class="form-group-modern">
                    <div class="input-icon-wrapper">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <input type="text" 
                               name="address" 
                               id="address"
                               class="form-input-modern @error('address') is-invalid @enderror" 
                               placeholder=" " 
                               value="{{ old('address') }}" 
                               required
                               autocomplete="street-address">
                        <label class="form-label-modern" for="address">Address</label>
                    </div>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password Fields -->
                <div class="password-row">
                    <!-- Password -->
                    <div class="form-group-modern">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-input-modern @error('password') is-invalid @enderror" 
                                   placeholder=" " 
                                   required
                                   autocomplete="new-password">
                            <label class="form-label-modern" for="password">Password</label>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group-modern">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation"
                                   class="form-input-modern @error('password_confirmation') is-invalid @enderror" 
                                   placeholder=" " 
                                   required
                                   autocomplete="new-password">
                            <label class="form-label-modern" for="password_confirmation">Confirm</label>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-register-modern" id="submitBtn">
                    <span id="btnText"><i class="fas fa-user-plus me-2"></i> Create Account</span>
                    <span id="btnLoader" style="display:none;" class="btn-spinner"></span>
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="auth-footer">
            <span class="auth-text">Already have an account? </span>
            <a href="{{ route('userLogin') }}" class="auth-link">Sign In</a>
        </div>
    </div>
</div>

<script>
    // Particle Animation
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            const size = Math.random() * 8 + 4;
            const startX = Math.random() * 100;
            const duration = Math.random() * 10 + 10;
            const delay = Math.random() * 5;
            
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${startX}%`;
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.background = `linear-gradient(135deg, rgba(102, 126, 234, ${Math.random() * 0.5 + 0.2}), rgba(118, 75, 162, ${Math.random() * 0.5 + 0.2}))`;
            particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite, pulse ${duration/2}s ease-in-out ${delay}s infinite`;
            
            particlesContainer.appendChild(particle);
        }
    }
    
    // Password Toggle
    function togglePassword(fieldId, iconElement) {
        const passwordInput = document.getElementById(fieldId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        }
    }
    
    // Form Submission with Loading
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';
    });
    
    // Initialize particles on load
    document.addEventListener('DOMContentLoaded', function() {
        createParticles();
    });
</script>

@endsection
