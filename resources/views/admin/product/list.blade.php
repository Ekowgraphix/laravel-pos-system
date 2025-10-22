@extends('admin.layouts.master')

@section('content')
<style>
    .product-image-preview {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #e2e8f0;
    }
    
    .product-image-preview:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        border-color: #667eea;
    }
    
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        animation: fadeIn 0.3s;
    }
    
    .image-modal-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    
    .image-modal-close {
        position: absolute;
        top: 20px;
        right: 40px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    
    .image-modal-close:hover {
        color: #667eea;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .image-not-found {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f7fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        color: #a0aec0;
        font-size: 1.5rem;
    }
</style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-box-open"></i>
                Product Management
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-store"></i> Manage your product inventory
            </p>
        </div>

        <!-- Product List Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-boxes"></i>
                    All Products
                </h5>
                <a href="{{ route('productCreate') }}" class="btn-primary-modern btn-modern">
                    <i class="fas fa-plus"></i>
                    Add Product
                </a>
            </div>
            <div class="card-body-modern">
                <!-- Search Bar -->
                <form action="{{route('productList')}}" method="get" class="mb-4">
                    <div class="search-bar-modern">
                        <input type="text" 
                               name="searchKey" 
                               placeholder="Search products by name..."
                               value="{{request('searchKey')}}">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Product Table -->
                <div class="table-modern-wrapper">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Product Name</th>
                                <th style="width: 15%; text-align: center;">Image</th>
                                <th style="width: 15%;">Price</th>
                                <th style="width: 15%;">Stock</th>
                                <th style="width: 20%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-block;">
                                            <img class="product-image-preview" 
                                                 src="{{ asset('productImages/' . $item->image) }}" 
                                                 alt="{{ $item->name }}"
                                                 onclick="openImageModal(this)"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-not-found\'><i class=\'fas fa-image\'></i></div>';"
                                                 title="Click to enlarge">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-success-modern badge-modern">
                                            <i class="fas fa-money-bill"></i>
                                            {{ number_format($item->price) }} GHS
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->count > 10)
                                            <span class="badge-success-modern badge-modern">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $item->count }} units
                                            </span>
                                        @elseif($item->count > 0)
                                            <span class="badge-warning-modern badge-modern">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ $item->count }} units
                                            </span>
                                        @else
                                            <span class="badge-danger-modern badge-modern">
                                                <i class="fas fa-times-circle"></i>
                                                Out of Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-modern" style="justify-content: center;">
                                            <a href="{{ route('productDetails', $item->id) }}" 
                                               class="action-btn-modern action-btn-view"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('productEdit', $item->id) }}" 
                                               class="action-btn-modern action-btn-edit"
                                               title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('productDelete', $item->id) }}" 
                                               class="action-btn-modern action-btn-delete"
                                               onclick="return confirm('Are you sure you want to delete this product?')"
                                               title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #9ca3af; font-weight: 600;">No products found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Image Preview Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
@endsection

@section('js-section')
<script>
    // Open image modal
    function openImageModal(img) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = img.src;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    // Close image modal
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection
