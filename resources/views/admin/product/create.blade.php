@extends('admin.layouts.master')

@section('content')
<style>
    .create-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
        border-color: #11998e;
        background: #f0fdf4;
        transform: translateY(-2px);
    }
    
    .image-upload-zone.drag-over {
        border-color: #11998e;
        background: #f0fdf4;
        box-shadow: 0 0 20px rgba(17, 153, 142, 0.3);
    }
    
    .product-preview {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #f7fafc;
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
        color: #11998e;
    }
    
    .form-control-modern {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control-modern:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.1);
    }
    
    .profit-calculator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    
    .profit-value {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .btn-modern {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-create {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
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
    
    .info-tip {
        background: #eef2ff;
        border-left: 4px solid #667eea;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
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
        <div class="create-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-plus-circle me-2"></i>Create New Product
                    </h4>
                    <p class="mb-0 opacity-75">Add a new product to your inventory</p>
                </div>
                <div>
                    <i class="fas fa-box-open fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding: 2rem;">
            <form action="{{ route('product#Create') }}" method="post" enctype="multipart/form-data" id="productCreateForm">
                @csrf
                
                <div class="row">
                    <!-- Left Column - Image & Category -->
                    <div class="col-lg-4">
                        <!-- Info Tip -->
                        <div class="info-tip">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle me-2 mt-1" style="color: #667eea;"></i>
                                <small style="color: #4a5568;">
                                    <strong>Quick Tip:</strong> Upload high-quality images and set competitive prices to maximize sales.
                                </small>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-folder"></i>
                                Category
                            </label>
                            <select name="categoryName" class="form-control form-control-modern @error('categoryName') is-invalid @enderror" required>
                                <option value="">Choose Category...</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" @if (old('categoryName') == $item->id)selected @endif>
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
                                <img class="product-preview no-image" src="{{ asset('defaultImg/default.jpg') }}" alt="Product" id="imagePreview">
                                <div class="mt-3" id="uploadInstructions">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #11998e;"></i>
                                    <p class="mb-1 mt-2" style="font-weight: 600; color: #4a5568;">Click to upload or drag image</p>
                                    <small style="color: #718096;">PNG, JPG, GIF up to 5MB</small>
                                </div>
                            </div>
                            <input type="file" name="image" id="imageInput" class="d-none @error('image') is-invalid @enderror" accept="image/*" onchange="handleImageSelect(event)" required>
                            @error('image')
                                <small class="text-danger d-block mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
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
                                                <span id="profitAmount">0.00</span> GHS
                                                <small style="font-size: 1rem; opacity: 0.8;">(<span id="profitMargin">0</span>%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-1 opacity-75">Potential Revenue</p>
                                    <div class="profit-value">
                                        <span id="potentialRevenue">0.00</span> GHS
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div class="form-modern">
                            <label>
                                <i class="fas fa-tag"></i>
                                Product Name
                            </label>
                            <input type="text" name="name" class="form-control form-control-modern @error('name') is-invalid @enderror" placeholder="Enter product name" value="{{old('name')}}" required>
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
                                        <input type="number" name="purchaseprice" id="purchasePrice" class="form-control form-control-modern @error('purchaseprice') is-invalid @enderror" placeholder="0.00" value="{{old('purchaseprice')}}" step="0.01" required style="border-left: none;">
                                    </div>
                                    <small class="text-muted">Cost per unit (GHS)</small>
                                    @error('purchaseprice')
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
                                        <input type="number" name="price" id="sellingPrice" class="form-control form-control-modern @error('price') is-invalid @enderror" placeholder="0.00" value="{{old('price')}}" step="0.01" required style="border-left: none;">
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
                                        Initial Stock
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; background: #f7fafc; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-boxes" style="color: #f59e0b;"></i>
                                        </span>
                                        <input type="number" name="count" id="stockCount" class="form-control form-control-modern @error('count') is-invalid @enderror" placeholder="0" value="{{old('count')}}" min="0" max="100" required style="border-left: none;">
                                    </div>
                                    <small class="text-muted">Opening inventory</small>
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
                            <textarea name="description" rows="5" class="form-control form-control-modern @error('description') is-invalid @enderror" placeholder="Enter detailed product description, features, specifications..." required>{{ old('description') }}</textarea>
                            <small class="text-muted">Provide detailed information to help customers</small>
                            @error('description')
                                <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn btn-modern btn-create w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Create Product
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
    
    // For create page, default is no image
    if (imagePreview.src && !imagePreview.src.includes('default.jpg')) {
        uploadZone.classList.add('has-image');
        imagePreview.classList.remove('no-image');
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
document.getElementById('productCreateForm').addEventListener('submit', function(e) {
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
