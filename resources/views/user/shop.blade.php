@extends('user.layouts.master')
@section('content')

<style>
    .shop-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    
    .shop-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .shop-hero h1 {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .search-bar-pro {
        max-width: 600px;
        margin: 30px auto 0;
        position: relative;
    }
    
    .search-input-pro {
        width: 100%;
        padding: 18px 60px 18px 24px;
        border-radius: 50px;
        border: none;
        font-size: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
    .search-input-pro:focus {
        outline: none;
        box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        transform: translateY(-2px);
    }
    
    .search-btn-pro {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        color: white;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    
    .search-btn-pro:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .filter-sidebar {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        position: sticky;
        top: 150px;
    }
    
    .filter-title {
        font-size: 20px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }
    
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .category-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .category-link {
        color: #495057;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 15px;
    }
    
    .category-link:hover {
        color: #667eea;
        transform: translateX(5px);
    }
    
    .category-link i {
        color: #667eea;
        font-size: 14px;
    }
    
    .price-filter {
        margin-top: 30px;
    }
    
    .price-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #e0e0f0;
        font-size: 14px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    
    .price-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .filter-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .filter-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
    }
    
    .product-card-pro {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 2px solid transparent;
    }
    
    .product-card-pro:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        border-color: #667eea;
    }
    
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 280px;
        background: #f8f9fa;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .product-card-pro:hover .product-image {
        transform: scale(1.1);
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .product-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
    }
    
    .product-card-pro:hover .product-actions {
        opacity: 1;
        transform: translateX(0);
    }
    
    .action-btn {
        width: 44px;
        height: 44px;
        background: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: #667eea;
        color: white;
        transform: scale(1.1);
    }
    
    .product-info {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-name {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-description {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 2px solid #f0f0f0;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }
    
    .add-cart-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .add-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    
    .empty-state i {
        font-size: 80px;
        color: #667eea;
        margin-bottom: 25px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .shop-hero h1 {
            font-size: 32px;
        }
        
        .filter-sidebar {
            margin-bottom: 30px;
            position: static;
        }
        
        .product-image-wrapper {
            height: 220px;
        }
    }
</style>

<!-- Hero Section -->
<div class="shop-hero">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center position-relative">
                <h1><i class="fas fa-store me-3"></i>Our Shop</h1>
                <p class="text-white-50" style="font-size: 18px;">Discover amazing products</p>
                
                <!-- Search Bar -->
                <form action="{{ route('shopList') }}" method="get" class="search-bar-pro">
                    @csrf
                    <input type="search" 
                           class="search-input-pro" 
                           value="{{ request('searchKey') }}"
                           name="searchKey" 
                           placeholder="Search for products...">
                    <button type="submit" class="search-btn-pro">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Shop Content -->
<div class="container my-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <!-- Categories -->
                <div class="mb-4">
                    <h4 class="filter-title"><i class="fas fa-th-large me-2"></i>Categories</h4>
                    <ul class="category-list">
                        <li class="category-item">
                            <a href="{{ route('shopList') }}" class="category-link">
                                <i class="fas fa-circle"></i>
                                <span>All Products</span>
                            </a>
                        </li>
                        @foreach ($categories as $item)
                            <li class="category-item">
                                <a href="{{ route('shopList', $item->id) }}" class="category-link">
                                    <i class="fas fa-circle"></i>
                                    <span>{{ $item->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Price Filter -->
                <div class="price-filter">
                    <h4 class="filter-title"><i class="fas fa-filter me-2"></i>Price Range</h4>
                    <form action="{{ route('shopList') }}" method="get">
                        @csrf
                        <input type="text" 
                               name="minPrice" 
                               value="{{ request('minPrice') }}"
                               class="price-input" 
                               placeholder="Min Price">
                        <input type="text" 
                               name="maxPrice" 
                               value="{{ request('maxPrice') }}"
                               class="price-input" 
                               placeholder="Max Price">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-check me-2"></i>Apply Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach ($products as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-card-pro">
                                <div class="product-image-wrapper">
                                    <a href="{{ route('shopDetails', $item->id) }}">
                                        <img src="{{ asset('productImages/' . $item->image) }}" 
                                             class="product-image" 
                                             alt="{{ $item->name }}">
                                    </a>
                                    
                                    @if($item->category_name)
                                        <div class="product-badge">{{ $item->category_name }}</div>
                                    @endif
                                    
                                    <div class="product-actions">
                                        <a href="{{ route('shopDetails', $item->id) }}" class="action-btn" title="Quick View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="action-btn" title="Add to Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="product-info">
                                    <a href="{{ route('shopDetails', $item->id) }}" style="text-decoration: none;">
                                        <h5 class="product-name">{{ $item->name }}</h5>
                                    </a>
                                    <p class="product-description">{{ Str::words($item->description, 10, '...') }}</p>
                                    
                                    <div class="product-footer">
                                        <div class="product-price">{{ number_format($item->price, 2) }} GHS</div>
                                        <form action="{{ route('addToCart') }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <input type="hidden" name="productID" value="{{ $item->id }}">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="add-cart-btn">
                                                <i class="fas fa-cart-plus"></i>
                                                <span>Add</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters or search term</p>
                    <a href="{{ route('shopList') }}" class="btn btn-pay mt-3">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
