@extends('user.layouts.master')

@section('content')

<style>
    /* Hero Section */
    .hero-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 800px;
        height: 800px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }
    
    .hero-title {
        font-size: 56px;
        font-weight: 900;
        color: white;
        line-height: 1.2;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .hero-subtitle {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 35px;
        line-height: 1.6;
    }
    
    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        background: white;
        color: #667eea;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .hero-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        color: #667eea;
    }
    
    .hero-carousel {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .hero-carousel img {
        height: 500px;
        object-fit: cover;
    }
    
    /* Featured Products */
    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .section-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .section-heading {
        font-size: 42px;
        font-weight: 900;
        color: white;
        margin-bottom: 15px;
    }
    
    .section-description {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Category Sidebar */
    .category-sidebar {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        height: 100%;
    }
    
    .category-title {
        font-size: 24px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }
    
    .category-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        color: #495057;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .category-link:hover {
        background: #f8f9fa;
        color: #667eea;
        transform: translateX(8px);
    }
    
    .category-link i {
        color: #667eea;
    }
    
    /* Product Card */
    .product-card-home {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.4s ease;
        height: 100%;
        border: 2px solid transparent;
    }
    
    .product-card-home:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        border-color: #667eea;
    }
    
    .product-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 250px;
    }
    
    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .product-card-home:hover .product-img-wrapper img {
        transform: scale(1.1);
    }
    
    .product-category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .product-info-home {
        padding: 25px;
    }
    
    .product-name-home {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 10px;
    }
    
    .product-desc-home {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .product-price-home {
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
    }
    
    .add-to-cart-home {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    .add-to-cart-home:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }
    
    /* Stats Counter */
    .stats-section {
        padding: 80px 0;
        background: white;
        margin: 60px 0;
        border-radius: 30px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
    }
    
    .stat-icon {
        font-size: 48px;
        color: white;
        margin-bottom: 15px;
    }
    
    .stat-number {
        font-size: 48px;
        font-weight: 900;
        color: white;
        margin-bottom: 10px;
    }
    
    .stat-label {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Testimonials */
    .testimonial-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin: 15px;
        transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }
    
    .testimonial-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #667eea;
    }
    
    .testimonial-name {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin-top: 15px;
    }
    
    .testimonial-stars {
        color: #ffc107;
        margin: 10px 0;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 36px;
        }
        
        .hero-carousel img {
            height: 300px;
        }
        
        .section-heading {
            font-size: 32px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-certificate me-2"></i>100% Reliable Services
                    </div>
                    <h1 class="hero-title">Your Trusted Shopping Destination</h1>
                    <p class="hero-subtitle">
                        Discover amazing products at unbeatable prices. Quality guaranteed, fast delivery, and excellent customer service.
                    </p>
                    <a href="{{route('shopList')}}" class="hero-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Start Shopping</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('customer/img/banner3.jpg') }}" class="d-block w-100" alt="Banner 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('customer/img/banner2.jpg') }}" class="d-block w-100" alt="Banner 2">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container-fluid products py-5" style="background: linear-gradient(to bottom, #1a1a2e 0%, #16213e 100%); margin-top: -30px;">
    <div class="container">
        <div class="section-title">
            <div class="section-badge">
                <i class="fas fa-star me-2"></i>Featured Products
            </div>
            <h2 class="section-heading">Our Products & Services</h2>
            <p class="section-description">Browse through our amazing collection of products</p>
        </div>
        
        <div class="row g-4">
            <!-- Category Sidebar -->
            <div class="col-lg-3">
                <div class="category-sidebar">
                    <h4 class="category-title">
                        <i class="fas fa-th-large me-2"></i>Categories
                    </h4>
                    @foreach ($category as $item)
                        <a href="{{ route('shopList', $item->id) }}" class="category-link">
                            <i class="fas fa-arrow-right"></i>
                            <span>{{ $item->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="row g-4">
                    @php $count = 1; @endphp
                    @foreach ($products as $item)
                        @if ($count <= 6)
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card-home">
                                    <div class="product-img-wrapper">
                                        <a href="{{ route('shopDetails', $item->id) }}">
                                            <img src="{{ asset('productImages/' . $item->image) }}" alt="{{ $item->name }}">
                                        </a>
                                        <div class="product-category-badge">{{ $item->category_name }}</div>
                                    </div>
                                    <div class="product-info-home">
                                        <a href="{{ route('shopDetails', $item->id) }}" style="text-decoration: none;">
                                            <h5 class="product-name-home">{{ $item->name }}</h5>
                                        </a>
                                        <p class="product-desc-home">{{ Str::words($item->description, 8, '...') }}</p>
                                        <div class="product-price-home">{{ number_format($item->price) }} GHS</div>
                                        <form action="{{ route('addToCart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="productID" value="{{ $item->id }}">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="add-to-cart-home">
                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php $count++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bestseller Products -->
<div class="container-fluid py-5" style="background: linear-gradient(to bottom, #16213e 0%, #1a1a2e 100%);">
    <div class="container">
        <div class="section-title">
            <div class="section-badge">
                <i class="fas fa-trophy me-2"></i>Top Sellers
            </div>
            <h2 class="section-heading">Bestseller Products</h2>
            <p class="section-description">Check out our top 3 best-selling products, loved by customers!</p>
        </div>
        
        @if(!empty($topProducts) && count($topProducts) > 0)
            <div class="row g-4">
                @foreach ($topProducts as $item)
                    <div class="col-lg-4">
                        <div class="product-card-home">
                            <div class="product-img-wrapper">
                                <a href="{{ route('shopDetails', $item->id) }}">
                                    <img src="{{ asset('productImages/' . $item->image) }}" alt="{{ $item->product_name }}">
                                </a>
                                <div class="product-category-badge">Bestseller</div>
                            </div>
                            <div class="product-info-home text-center">
                                <h5 class="product-name-home">{{ $item->product_name }}</h5>
                                <div class="product-price-home">{{ number_format($item->price) }} GHS</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div style="background: white; border-radius: 20px; padding: 40px; display: inline-block;">
                    <i class="fas fa-box-open" style="font-size: 60px; color: #667eea; opacity: 0.5;"></i>
                    <p class="mt-3 mb-0" style="color: #6c757d;">Not enough data to show bestsellers yet!</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Statistics Section -->
<div class="container my-5">
    <div class="stats-section">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">{{ $customerCount }}</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Quality Service</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                    <div class="stat-number">33</div>
                    <div class="stat-label">Certificates</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                    <div class="stat-number">{{ count($products) }}</div>
                    <div class="stat-label">Products</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials -->
<div class="container-fluid py-5" style="background: linear-gradient(to bottom, #1a1a2e 0%, #16213e 100%);">
    <div class="container">
        <div class="section-title">
            <div class="section-badge">
                <i class="fas fa-quote-left me-2"></i>Testimonials
            </div>
            <h2 class="section-heading">What Our Clients Say</h2>
            <p class="section-description">Real feedback from real customers</p>
        </div>
        
        <div class="owl-carousel testimonial-carousel">
            @foreach ($rating as $item)
                <div class="testimonial-card">
                    <div class="text-center">
                        @if ($item->profile != null)
                            <img src="{{ asset('userProfile/' . $item->profile) }}" class="testimonial-avatar" alt="{{ $item->name }}">
                        @else
                            <img src="{{asset('customer/img/testimonial-1.jpg')}}" class="testimonial-avatar" alt="{{ $item->name }}">
                        @endif
                        <h5 class="testimonial-name">{{ $item->name }}</h5>
                        <div class="testimonial-stars">
                            @php $stars = number_format($item->count); @endphp
                            @for ($i = 1; $i <= $stars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for ($j = $stars + 1; $j <= 5; $j++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <p style="color: #6c757d; font-style: italic;">
                            "Lorem Ipsum is simply dummy text of the printing industry. Ipsum has been the industry's standard dummy text ever since the 1500s."
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
