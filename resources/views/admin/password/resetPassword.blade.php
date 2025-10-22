@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-key"></i>
                Reset Admin Password
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-shield-alt"></i> Reset password for administrator accounts
            </p>
        </div>

        <!-- Reset Password Form Card -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <h5 class="card-title-modern">
                            <i class="fas fa-unlock-alt"></i>
                            Password Reset Request
                        </h5>
                    </div>

                    <form action="{{ route('resetPassword') }}" method="POST">
                        @csrf
                        <div class="card-body-modern">
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert-modern alert-success-modern">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Error Message -->
                            @if(session('error'))
                                <div class="alert-modern alert-danger-modern">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Info Alert -->
                            <div class="alert-modern alert-info-modern">
                                <i class="fas fa-info-circle"></i>
                                <strong>How it works:</strong> Enter the email address of the admin account you want to reset. A new temporary password will be generated.
                            </div>

                            <!-- Email Field -->
                            <div class="form-group-modern">
                                <label for="email" class="form-label-modern">
                                    <i class="fas fa-envelope"></i> Admin Email Address *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       value="{{ old('email') }}"
                                       class="form-control-modern @error('email') is-invalid @enderror" 
                                       placeholder="Enter admin email address"
                                       autofocus>
                                @error('email')
                                    <small class="text-danger d-block mt-2" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </small>
                                @enderror
                                <small class="d-block mt-2" style="color: #6b7280; font-size: 13px;">
                                    <i class="fas fa-lightbulb"></i> Make sure this email exists in the system
                                </small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button type="submit" class="btn-warning-modern btn-modern w-100">
                                        <i class="fas fa-redo"></i>
                                        Reset Password
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('adminDashboard') }}" class="btn-dark-modern btn-modern w-100">
                                        <i class="fas fa-arrow-left"></i>
                                        Back to Dashboard
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
                            <i class="fas fa-exclamation-triangle" style="color: #f5576c;"></i> Important Security Notes
                        </h6>
                        <ul style="color: #4a5568; font-size: 14px; line-height: 1.8; margin: 0; padding-left: 20px;">
                            <li>Only authorized superadmins can reset passwords</li>
                            <li>The admin will receive a new temporary password</li>
                            <li>They should change it immediately after login</li>
                            <li>This action will be logged for security purposes</li>
                            <li>Use this feature responsibly</li>
                        </ul>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card-modern" style="margin-top: 20px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border: 2px solid rgba(102, 126, 234, 0.2);">
                    <div class="card-body-modern">
                        <h6 style="color: #667eea; font-weight: 700; margin-bottom: 10px;">
                            <i class="fas fa-question-circle"></i> Need Help?
                        </h6>
                        <p style="color: #4a5568; font-size: 14px; margin: 0; line-height: 1.6;">
                            If you're unable to reset a password or encounter any issues, please contact the system administrator or check the admin list to verify the email address.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
