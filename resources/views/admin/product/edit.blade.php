@extends('admin.layouts.master')

@section('content')
<style>
    .edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem;
        border-radius: 15px 15px 0 0;
        color: white;
        margin: -1.25rem -1.25rem 1.5rem -1.25rem;
    }
    
    .image-upload-zone {
        border: 3px dashed #cbd5e0;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        background: #f7fafc;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }
    
    .image-upload-zone:hover {
        border-color: #667eea;
        background: #eef2ff;
        transform: translateY(-2px);
    }
    
    .image-upload-zone.drag-over {
        border-color: #667eea;
        background: #eef2ff;
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
    }
    
    .product-preview {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: block;
    }
    
    .product-preview.has-image {
        display: block;
    }
    
    .product-preview.no-image {
        display: none;
    }
    
    #uploadInstructions {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        pointer-events: none;
    }
    
    .image-upload-zone.has-image #uploadInstructions {
        display: none;
    }
    
    .image-upload-zone.has-image:hover #uploadInstructions {
        display: block;
        background: rgba(0,0,0,0.7);
        padding: 1rem;
        border-radius: 10px;
    }
    
    .image-upload-zone.has-image:hover #uploadInstructions * {
        color: white !important;
    }
    
    .image-upload-zone.has-image:hover .product-preview {
        opacity: 0.3;
    }
    
    .form-modern {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
    }
    
    .form-modern label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .form-modern label i {
        margin-right: 0.5rem;
        color: #667eea;
    }
    
    .form-control-modern {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .profit-calculator {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    
    .profit-value {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .stock-indicator {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .stock-high { background: #d4edda; color: #155724; }
    .stock-medium { background: #fff3cd; color: #856404; }
    .stock-low { background: #f8d7da; color: #721c24; }
    
    .btn-modern {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #e2e8f0;
        color: #4a5568;
    }
    
    .btn-cancel:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
        color: #2d3748;
    }
    
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #eef2ff;
        border-radius: 10px;
        font-size: 0.875rem;
        color: #5a67d8;
        margin-bottom: 0.5rem;
    }
    
    .quick-stats {
        background: #f7fafc;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .quick-stat-item {
        text-align: center;
        padding: 0.75rem;
    }
    
    .quick-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .quick-stat-label {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid">
    <div class="card shadow-lg mb-4" style="border: none; border-radius: 15px;">
        <div class="edit-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </h4>
                    <p class="mb-0 opacity-75">Update product information and pricing</p>
                </div>
                <div class="info-badge" style="background: rgba(255,255,255,0.2); color: white;">
                    <i class="fas fa-barcode"></i>
                    <span>ID: #{{$products->id}}</span>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding: 2rem;">
            <form action="{{ route('productUpdate') }}" method="post" enctype="multipart/form-data" id="productEditForm">
                @csrf
                <input type="hidden" name="oldImage" value="{{$products->image}}">
                <input type="hidden" name="productID" value="{{$products->id}}">
                
                <div class="row">
                    <!-- Left Column - Image & Category -->
                    <div class="col-lg-4">
                        <!-- Category Selection -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-folder"></i>
                                Category
                            </label>
                            <select name="categoryName" class="form-control form-control-modern @error('categoryName') is-invalid @enderror">
                                <option value="">Choose Category...</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" @if (old('categoryName',$products->category_id) == $item->id)selected @endif>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoryName')
                                <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-image"></i>
                                Product Image
                            </label>
                            <div class="image-upload-zone" id="imageUploadZone" onclick="document.getElementById('imageInput').click()">
                                <img class="product-preview" src="{{ asset('productImages/'. $products->image) }}" alt="Product" id="imagePreview">
                                <div class="mt-3" id="uploadInstructions">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #667eea;"></i>
                                    <p class="mb-1 mt-2" style="font-weight: 600; color: #4a5568;">Click to upload or drag image</p>
                                    <small style="color: #718096;">PNG, JPG, GIF up to 5MB</small>
                                </div>
                            </div>
                            <input type="file" name="image" id="imageInput" class="d-none @error('image') is-invalid @enderror" accept="image/*" onchange="handleImageSelect(event)">
                            @error('image')
                                <small class="text-danger d-block mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Current Stock Status -->
                        <div class="text-center mt-3">
                            <p class="mb-2" style="font-weight: 600; color: #4a5568;">Current Stock Status</p>
                            @if($products->count > 50)
                                <span class="stock-indicator stock-high">
                                    <i class="fas fa-check-circle me-1"></i>In Stock ({{$products->count}} units)
                                </span>
                            @elseif($products->count > 20)
                                <span class="stock-indicator stock-medium">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Medium ({{$products->count}} units)
                                </span>
                            @else
                                <span class="stock-indicator stock-low">
                                    <i class="fas fa-times-circle me-1"></i>Low Stock ({{$products->count}} units)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Product Details -->
                    <div class="col-lg-8">
                        <!-- Profit Calculator -->
                        <div class="profit-calculator">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calculator fa-2x me-3"></i>
                                        <div>
                                            <p class="mb-1 opacity-75">Estimated Profit per Unit</p>
                                            <div class="profit-value">
                                                <span id="profitAmount">{{ number_format($products->price - $products->purchase_price) }}</span> GHS
                                                <small style="font-size: 1rem; opacity: 0.8;">(<span id="profitMargin">{{ $products->purchase_price > 0 ? number_format((($products->price - $products->purchase_price) / $products->purchase_price) * 100, 1) : 0 }}</span>%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-1 opacity-75">Potential Revenue</p>
                                    <div class="profit-value">
                                        <span id="potentialRevenue">{{ number_format(($products->price - $products->purchase_price) * $products->count) }}</span> GHS
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <div class="row">
                                <div class="col-4 quick-stat-item">
                                    <div class="quick-stat-value">{{ $products->category_name }}</div>
                                    <div class="quick-stat-label">Category</div>
                                </div>
                                <div class="col-4 quick-stat-item">
                                    <div class="quick-stat-value" id="currentStock">{{ $products->count }}</div>
                                    <div class="quick-stat-label">Current Stock</div>
                                </div>
                                <div class="col-4 quick-stat-item">
                                    <div class="quick-stat-value">{{ number_format($products->price * $products->count) }} GHS</div>
                                    <div class="quick-stat-label">Total Value</div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-tag"></i>
                                Product Name
                            </label>
                            <input type="text" name="name" class="form-control form-control-modern @error('name') is-invalid @enderror" placeholder="Enter product name" value="{{old('name',$products->name)}}" required>
                            @error('name')
                                <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Pricing Row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-modern">
                                    <label>
                                        <i class="fas fa-shopping-cart"></i>
                                        Purchase Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; background: #f7fafc; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-coins" style="color: #667eea;"></i>
                                        </span>
                                        <input type="number" name="purchaseprice" id="purchasePrice" class="form-control form-control-modern @error('purchase_price') is-invalid @enderror" placeholder="0.00" value="{{old('purchase_price',$products->purchase_price)}}" step="0.01" required style="border-left: none;">
                                    </div>
                                    <small class="text-muted">Cost per unit (GHS)</small>
                                    @error('purchase_price')
                                        <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-modern">
                                    <label>
                                        <i class="fas fa-dollar-sign"></i>
                                        Selling Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; background: #f7fafc; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-money-bill-wave" style="color: #11998e;"></i>
                                        </span>
                                        <input type="number" name="price" id="sellingPrice" class="form-control form-control-modern @error('price') is-invalid @enderror" placeholder="0.00" value="{{old('price',$products->price)}}" step="0.01" required style="border-left: none;">
                                    </div>
                                    <small class="text-muted">Retail price (GHS)</small>
                                    @error('price')
                                        <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-modern">
                                    <label>
                                        <i class="fas fa-warehouse"></i>
                                        Stock Quantity
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; background: #f7fafc; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-boxes" style="color: #f59e0b;"></i>
                                        </span>
                                        <input type="number" name="count" id="stockCount" class="form-control form-control-modern @error('count') is-invalid @enderror" placeholder="0" value="{{old('count',$products->count)}}" min="0" max="100" required style="border-left: none;">
                                    </div>
                                    <small class="text-muted">Available units</small>
                                    @error('count')
                                        <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-align-left"></i>
                                Product Description
                            </label>
                            <textarea name="description" rows="5" class="form-control form-control-modern @error('description')is-invalid @enderror" placeholder="Enter detailed product description, features, specifications..." required>{{old('description',$products->description)}}</textarea>
                            <small class="text-muted">Provide detailed information about the product</small>
                            @error('description')
                                <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn btn-modern btn-update w-100">
                                    <i class="fas fa-save me-2"></i>Update Product
                                </button>
                            </div>
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('productList') }}" class="btn btn-modern btn-cancel w-100">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Check if image exists and set proper classes on page load
document.addEventListener('DOMContentLoaded', function() {
    checkImageExists();
});

function checkImageExists() {
    const imagePreview = document.getElementById('imagePreview');
    const uploadZone = document.getElementById('imageUploadZone');
    const defaultImagePath = '{{ asset("defaultImg/default.jpg") }}';
    
    // Check if the current image is not the default
    if (imagePreview.src && !imagePreview.src.includes('default.jpg')) {
        uploadZone.classList.add('has-image');
        imagePreview.classList.add('has-image');
    } else {
        uploadZone.classList.remove('has-image');
        imagePreview.classList.add('no-image');
    }
}

// Real-time profit calculation
function calculateProfit() {
    const purchasePrice = parseFloat(document.getElementById('purchasePrice').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('sellingPrice').value) || 0;
    const stockCount = parseFloat(document.getElementById('stockCount').value) || 0;
    
    const profit = sellingPrice - purchasePrice;
    const margin = purchasePrice > 0 ? ((profit / purchasePrice) * 100) : 0;
    const potentialRevenue = profit * stockCount;
    
    document.getElementById('profitAmount').textContent = profit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('profitMargin').textContent = margin.toFixed(1);
    document.getElementById('potentialRevenue').textContent = potentialRevenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('currentStock').textContent = stockCount;
}

// Add event listeners
document.getElementById('purchasePrice').addEventListener('input', calculateProfit);
document.getElementById('sellingPrice').addEventListener('input', calculateProfit);
document.getElementById('stockCount').addEventListener('input', calculateProfit);

// Image upload handling
function handleImageSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const uploadZone = document.getElementById('imageUploadZone');
            
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('no-image');
            imagePreview.classList.add('has-image');
            uploadZone.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }
}

// Drag and drop functionality
const uploadZone = document.getElementById('imageUploadZone');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('drag-over');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('drag-over');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('imageInput').files = files;
        handleImageSelect({ target: { files: files } });
    }
});

// Form validation
document.getElementById('productEditForm').addEventListener('submit', function(e) {
    const purchasePrice = parseFloat(document.getElementById('purchasePrice').value);
    const sellingPrice = parseFloat(document.getElementById('sellingPrice').value);
    
    if (sellingPrice < purchasePrice) {
        if (!confirm('Selling price is lower than purchase price. This will result in a loss. Continue?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
@endsection
