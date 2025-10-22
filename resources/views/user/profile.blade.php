@extends('user.layouts.master')

@section('content')

<style>
    .profile-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 60px;
        margin-bottom: 60px;
    }
    
    .profile-hero h1 {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
    }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: none;
    }
    
    .profile-title {
        font-size: 28px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 3px solid #667eea;
    }
    
    .profile-image-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .profile-img-preview {
        width: 200px;
        height: 200px;
        border-radius: 20px;
        object-fit: cover;
        border: 4px solid #667eea;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
    }
    
    .profile-img-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 40px rgba(0,0,0,0.18);
    }
    
    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        margin-top: 15px;
        width: 100%;
    }
    
    .upload-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
    }
    
    .upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    
    .form-label-profile {
        font-weight: 600;
        color: #212529;
        margin-bottom: 10px;
        font-size: 15px;
    }
    
    .form-control-profile {
        padding: 14px 18px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .form-control-profile:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .form-control-profile:disabled {
        background: #f8f9fa;
        cursor: not-allowed;
    }
    
    .update-btn-profile {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .update-btn-profile:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }
    
    .change-password-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 50px;
        background: rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }
    
    .change-password-link:hover {
        background: rgba(102, 126, 234, 0.2);
        transform: translateX(5px);
        color: #667eea;
    }
</style>

<!-- Hero Section -->
<div class="profile-hero">
    <div class="container">
        <div class="text-center">
            <h1><i class="fas fa-user-circle me-3"></i>Your Profile</h1>
            <p class="text-white-50" style="font-size: 18px;">Manage your account information</p>
        </div>
    </div>
</div>

    <!-- Profile Content -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="profile-card">
                    <h3 class="profile-title">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </h3>
                    
                    <form action="{{ route('profileUpdate') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Profile Image Column -->
                            <div class="col-lg-3">
                                <div class="text-center">
                                    <input type="hidden" name="oldImage" value="{{auth()->user()->profile}}">
                                    
                                    <div class="profile-image-wrapper mb-3">
                                        @if (auth()->user()->profile == null)
                                            <img class="profile-img-preview" id="output" src="{{asset('admin/img/undraw_profile.svg')}}" alt="Profile">
                                        @else
                                            <img class="profile-img-preview" id="output" src="{{asset('userProfile/'. auth()->user()->profile)}}" alt="Profile">
                                        @endif
                                    </div>
                                    
                                    <div class="upload-btn-wrapper">
                                        <button class="upload-btn" type="button">
                                            <i class="fas fa-camera"></i>
                                            <span>Choose Photo</span>
                                        </button>
                                        <input type="file" name="image" class="@error('image') is-invalid @enderror" onchange="loadFile(event)" accept="image/*">
                                    </div>
                                    
                                    @error('image')
                                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Form Fields Column -->
                            <div class="col-lg-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-profile">
                                            <i class="fas fa-user me-2"></i>Full Name
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               @if (auth()->user()->provider != 'simple') disabled @endif
                                               class="form-control-profile @error('name') is-invalid @enderror"
                                               placeholder="Enter your name" 
                                               value="{{old('name',auth()->user()->name == null ? auth()->user()->nickname : auth()->user()->name)}}">
                                        @error('name')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label-profile">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               @if (auth()->user()->provider != 'simple') disabled @endif
                                               class="form-control-profile @error('email') is-invalid @enderror"
                                               placeholder="Enter your email" 
                                               value="{{auth()->user()->email}}">
                                        @error('email')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label-profile">
                                            <i class="fas fa-phone me-2"></i>Phone Number
                                        </label>
                                        <input type="text" 
                                               name="phone"
                                               class="form-control-profile @error('phone') is-invalid @enderror"
                                               placeholder="09xxxxxxxx" 
                                               value="{{old('phone',auth()->user()->phone)}}">
                                        @error('phone')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label-profile">
                                            <i class="fas fa-map-marker-alt me-2"></i>Address
                                        </label>
                                        <input type="text" 
                                               name="address"
                                               class="form-control-profile @error('address') is-invalid @enderror"
                                               placeholder="Enter your address" 
                                               value="{{old('address',auth()->user()->address)}}">
                                        @error('address')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    @if (auth()->user()->provider == 'simple')
                                    <div class="col-12">
                                        <a href="{{route('changePassword')}}" class="change-password-link">
                                            <i class="fas fa-key"></i>
                                            <span>Change Password</span>
                                        </a>
                                    </div>
                                    @endif
                                    
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="update-btn-profile">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
// Image Preview Function
var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src); // Free memory
    }
    
    // Show notification
    if(typeof showNotification === 'function') {
        showNotification('Image selected! Click Update Profile to save.', 'info');
    }
};
</script>

@endsection
