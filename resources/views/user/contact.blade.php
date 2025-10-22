@extends('user.layouts.master')
@section('content')

<style>
    .contact-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 60px;
        margin-bottom: 60px;
    }
    
    .contact-hero h1 {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
    }
    
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: none;
    }
    
    .contact-title {
        font-size: 28px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #667eea;
    }
    
    .form-label-pro {
        font-weight: 600;
        color: #212529;
        margin-bottom: 10px;
        font-size: 15px;
    }
    
    .form-control-pro {
        padding: 14px 18px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .form-control-pro:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .submit-btn-pro {
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
    
    .submit-btn-pro:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }
    
    .contact-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px 30px;
        color: white;
        height: 100%;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .info-item {
        display: flex;
        align-items: start;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    
    .info-content h5 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .info-content p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }
</style>

<!-- Hero Section -->
<div class="contact-hero">
    <div class="container">
        <div class="text-center">
            <h1><i class="fas fa-envelope me-3"></i>Contact Us</h1>
            <p class="text-white-50" style="font-size: 18px;">We'd love to hear from you!</p>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="container mb-5">
    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="contact-card">
                <h3 class="contact-title">
                    <i class="fas fa-paper-plane me-2"></i>Send Us a Message
                </h3>
                
                <form method="post" action="{{route('sendMessage')}}">
                    @csrf
                    <div class="mb-4">
                        <label for="subject" class="form-label-pro">
                            <i class="fas fa-tag me-2"></i>Subject
                        </label>
                        <input type="text" 
                               name="subject" 
                               id="subject" 
                               value="{{old('subject')}}" 
                               class="form-control form-control-pro" 
                               placeholder="What's this about?" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label-pro">
                            <i class="fas fa-comment-dots me-2"></i>Message
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="6" 
                                  class="form-control form-control-pro" 
                                  placeholder="Write your message here..." 
                                  required>{{old('message')}}</textarea>
                    </div>

                    <button type="submit" class="submit-btn-pro">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>

                    @if (session('success'))
                        <div class="alert alert-success mt-4" style="border-radius: 12px; border: none; background: #d4edda; color: #155724;">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="contact-info-card">
                <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 30px;">
                    <i class="fas fa-info-circle me-2"></i>Get In Touch
                </h3>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h5>Our Location</h5>
                        <p>123 Business Street<br>Yangon, Myanmar</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h5>Email Us</h5>
                        <p>support@modernpos.com<br>sales@modernpos.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h5>Call Us</h5>
                        <p>+959 123 456 789<br>+959 987 654 321</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h5>Working Hours</h5>
                        <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
