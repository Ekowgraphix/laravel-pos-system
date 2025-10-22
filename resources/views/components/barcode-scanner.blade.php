<!-- Barcode Scanner Component -->
<div class="barcode-scanner-wrapper">
    <div class="mb-3">
        <label for="barcodeInput" class="form-label">
            <i class="fa-solid fa-barcode me-2"></i>Scan or Enter Barcode
        </label>
        <div class="input-group">
            <input 
                type="text" 
                id="barcodeInput" 
                class="form-control" 
                placeholder="Scan barcode or enter manually..."
                autocomplete="off"
                autofocus
            >
            <button class="btn btn-primary" type="button" id="startCamera">
                <i class="fa-solid fa-camera me-1"></i>Use Camera
            </button>
            <button class="btn btn-secondary" type="button" id="manualEntry">
                <i class="fa-solid fa-keyboard me-1"></i>Manual
            </button>
        </div>
        <small class="text-muted">
            Use a USB barcode scanner or click "Use Camera" for mobile scanning
        </small>
    </div>

    <!-- Camera Scanner Modal -->
    <div class="modal fade" id="cameraScannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-camera me-2"></i>Camera Barcode Scanner
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cameraView" class="text-center">
                        <video id="preview" style="width: 100%; max-width: 500px;"></video>
                        <div class="mt-3">
                            <p class="text-muted">Position barcode within the camera view</p>
                            <div id="scanResult" class="alert alert-success d-none"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanned Product Display -->
    <div id="scannedProduct" class="card mt-3 d-none">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <img id="productImage" src="" alt="Product" class="img-fluid rounded">
                </div>
                <div class="col-md-10">
                    <h5 id="productName" class="mb-2"></h5>
                    <p class="mb-1">
                        <strong>Price:</strong> <span id="productPrice"></span>
                    </p>
                    <p class="mb-1">
                        <strong>Stock:</strong> <span id="productStock"></span>
                    </p>
                    <p class="mb-1">
                        <strong>Barcode:</strong> <span id="productBarcode"></span>
                    </p>
                    <div class="mt-2">
                        <button class="btn btn-success btn-sm" id="addToCart">
                            <i class="fa-solid fa-cart-plus me-1"></i>Add to Cart
                        </button>
                        <button class="btn btn-secondary btn-sm" id="clearScan">
                            <i class="fa-solid fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.barcode-scanner-wrapper {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
}

#preview {
    border: 3px solid #0d6efd;
    border-radius: 10px;
}

#scannedProduct {
    border-left: 4px solid #198754;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const startCameraBtn = document.getElementById('startCamera');
    const manualEntryBtn = document.getElementById('manualEntry');
    const cameraScannerModal = new bootstrap.Modal(document.getElementById('cameraScannerModal'));
    const preview = document.getElementById('preview');
    const scannedProduct = document.getElementById('scannedProduct');
    
    let stream = null;
    let scanning = false;

    // USB Scanner - Listen for Enter key
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const barcode = this.value.trim();
            if (barcode) {
                searchProduct(barcode);
            }
        }
    });

    // Manual entry
    manualEntryBtn.addEventListener('click', function() {
        const barcode = barcodeInput.value.trim();
        if (barcode) {
            searchProduct(barcode);
        } else {
            alert('Please enter a barcode');
        }
    });

    // Camera scanner
    startCameraBtn.addEventListener('click', function() {
        cameraScannerModal.show();
        startCameraScanner();
    });

    document.getElementById('cameraScannerModal').addEventListener('hidden.bs.modal', function() {
        stopCameraScanner();
    });

    // Start camera scanner
    function startCameraScanner() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function(s) {
                stream = s;
                preview.srcObject = stream;
                preview.play();
                scanning = true;
                scanBarcode();
            })
            .catch(function(err) {
                alert('Camera access denied: ' + err.message);
            });
    }

    // Stop camera scanner
    function stopCameraScanner() {
        scanning = false;
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // Scan barcode from camera
    function scanBarcode() {
        if (!scanning) return;

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        if (preview.readyState === preview.HAVE_ENOUGH_DATA) {
            canvas.width = preview.videoWidth;
            canvas.height = preview.videoHeight;
            context.drawImage(preview, 0, 0, canvas.width, canvas.height);
            
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                document.getElementById('scanResult').textContent = 'Barcode detected: ' + code.data;
                document.getElementById('scanResult').classList.remove('d-none');
                
                barcodeInput.value = code.data;
                searchProduct(code.data);
                
                setTimeout(() => {
                    cameraScannerModal.hide();
                }, 1000);
                return;
            }
        }

        requestAnimationFrame(scanBarcode);
    }

    // Search product by barcode
    function searchProduct(barcode) {
        // Show loading
        scannedProduct.classList.add('d-none');

        // API call to find product
        fetch(`/api/v1/products/barcode/${barcode}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayProduct(data.data);
                } else {
                    alert('Product not found');
                    barcodeInput.value = '';
                    barcodeInput.focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error searching product');
            });
    }

    // Display product
    function displayProduct(product) {
        document.getElementById('productImage').src = product.image || '/images/default-product.png';
        document.getElementById('productName').textContent = product.name;
        document.getElementById('productPrice').textContent = product.price + ' MMK';
        document.getElementById('productStock').textContent = product.count + ' units';
        document.getElementById('productBarcode').textContent = product.barcode;

        scannedProduct.classList.remove('d-none');
        scannedProduct.dataset.productId = product.id;
    }

    // Add to cart
    document.getElementById('addToCart').addEventListener('click', function() {
        const productId = scannedProduct.dataset.productId;
        // Implement your add to cart logic here
        console.log('Adding product to cart:', productId);
        alert('Product added to cart!');
        clearScan();
    });

    // Clear scan
    document.getElementById('clearScan').addEventListener('click', clearScan);

    function clearScan() {
        barcodeInput.value = '';
        scannedProduct.classList.add('d-none');
        barcodeInput.focus();
    }

    // Auto-focus on barcode input
    barcodeInput.focus();
});
</script>
