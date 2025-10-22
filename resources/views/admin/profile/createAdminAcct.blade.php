@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-user-plus"></i>
                Create Admin Account
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-user-shield"></i> Add a new administrator to the system
            </p>
        </div>

        <!-- Create Admin Form Card -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-user-cog"></i>
                            Administrator Information
                        </h5>
                    </div>

                    <form action="{{ route('createAdmin') }}" method="POST">
                        @csrf
                        <div class="card-body-modern">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Info Alert -->
                            <div class="alert-modern alert-info-modern">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> This account will have administrator privileges. Choose a strong password.
                            </div>

                            <!-- Name Field -->
                            <div class="form-group-modern">
                                <label for="name" class="form-label-modern">
                                    <i class="fas fa-user"></i> Full Name *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="form-control-modern @error('name') is-invalid @enderror" 
                                       placeholder="Enter administrator full name">
                                @error('name')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="form-group-modern">
                                <label for="email" class="form-label-modern">
                                    <i class="fas fa-envelope"></i> Email Address *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       value="{{ old('email') }}"
                                       class="form-control-modern @error('email') is-invalid @enderror" 
                                       placeholder="Enter email address">
                                @error('email')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="form-group-modern">
                                <label for="password" class="form-label-modern">
                                    <i class="fas fa-lock"></i> Password *
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control-modern @error('password') is-invalid @enderror" 
                                       placeholder="Enter secure password (min. 8 characters)">
                                @error('password')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                                <small class="d-block mt-2" style="color: #6b7280; font-size: 13px;">
                                    <i class="fas fa-shield-alt"></i> Use a combination of letters, numbers, and symbols
                                </small>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group-modern">
                                <label for="confirmpassword" class="form-label-modern">
                                    <i class="fas fa-lock"></i> Confirm Password *
                                </label>
                                <input type="password" 
                                       name="confirmpassword" 
                                       id="confirmpassword"
                                       class="form-control-modern @error('confirmpassword') is-invalid @enderror" 
                                       placeholder="Re-enter password">
                                @error('confirmpassword')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button type="submit" class="btn-success-modern btn-modern w-100">
                                        <i class="fas fa-user-plus"></i>
                                        Create Admin Account
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('adminDashboard')}}" class="btn-dark-modern btn-modern w-100">
                                        <i class="fas fa-times"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Notice Card -->
                <div class="card-modern" style="margin-top: 20px;">
                    <div class="card-body-modern">
                        <h6 style="color: #2d3748; font-weight: 700; margin-bottom: 15px;">
                            <i class="fas fa-shield-alt" style="color: #667eea;"></i> Security Guidelines
                        </h6>
                        <ul style="color: #4a5568; font-size: 14px; line-height: 1.8; margin: 0; padding-left: 20px;">
                            <li>Use a strong, unique password with at least 8 characters</li>
                            <li>Include uppercase, lowercase, numbers, and special characters</li>
                            <li>Never share admin credentials with unauthorized users</li>
                            <li>Admin accounts have full access to system settings</li>
                            <li>Regularly update passwords for security</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
