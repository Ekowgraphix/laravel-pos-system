# 📘 Laravel POS - REST API Documentation

**Version**: 1.0.0  
**Base URL**: `http://your-domain.com/api/v1`  
**Authentication**: Laravel Sanctum (Bearer Token)

---

## 🔐 Authentication

### Get API Token
```bash
# Login to get token
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}

# Response
{
  "token": "1|abc123...",
  "user": {...}
}
```

### Use Token
```bash
# Add to header
Authorization: Bearer 1|abc123...
```

---

## 📦 Products API

### List Products
```
GET /api/v1/products
```

**Parameters:**
- `category_id` (optional) - Filter by category
- `store_id` (optional) - Filter by store
- `low_stock` (optional) - Show only low stock items
- `search` (optional) - Search by name/barcode/SKU
- `per_page` (optional) - Items per page (default: 15)

**Example:**
```bash
curl "http://localhost:8000/api/v1/products?category_id=1&low_stock=true"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Product Name",
        "price": 1000,
        "count": 50,
        "barcode": "123456789",
        "category": {...},
        "store": {...}
      }
    ],
    "total": 100
  }
}
```

### Get Single Product
```
GET /api/v1/products/{id}
```

### Find by Barcode
```
GET /api/v1/products/barcode/{barcode}
```

### Create Product (Auth Required)
```
POST /api/v1/products
```

**Body:**
```json
{
  "name": "New Product",
  "price": 1000,
  "purchase_price": 500,
  "category_id": 1,
  "count": 100,
  "description": "Product description",
  "low_stock_threshold": 10,
  "barcode": "123456789",
  "sku": "PROD-001"
}
```

### Update Product (Auth Required)
```
PUT /api/v1/products/{id}
```

### Delete Product (Auth Required)
```
DELETE /api/v1/products/{id}
```

### Low Stock Products (Auth Required)
```
GET /api/v1/products/low-stock/list
```

### Expiring Products (Auth Required)
```
GET /api/v1/products/expiring/list?days=30
```

---

## 🛒 Orders API

### List Orders (Auth Required)
```
GET /api/v1/orders
```

**Parameters:**
- `user_id` - Filter by user
- `status` - Filter by status (0=pending, 1=success, 2=reject)
- `payment_status` - Filter by payment status
- `date_from` - Start date
- `date_to` - End date
- `per_page` - Items per page

### Get Single Order (Auth Required)
```
GET /api/v1/orders/{id}
```

### Create Order (Auth Required)
```
POST /api/v1/orders
```

**Body:**
```json
{
  "user_id": 1,
  "product_id": 1,
  "count": 2
}
```

### Update Order Status (Auth Required)
```
PATCH /api/v1/orders/{id}/status
```

**Body:**
```json
{
  "status": "1",
  "reject_reason": "Optional reason if rejected"
}
```

### Order Statistics (Auth Required)
```
GET /api/v1/orders/statistics/summary?date_from=2025-01-01&date_to=2025-12-31
```

**Response:**
```json
{
  "success": true,
  "data": {
    "total_orders": 150,
    "pending_orders": 10,
    "completed_orders": 130,
    "rejected_orders": 10,
    "total_revenue": 150000,
    "average_order_value": 1000
  }
}
```

---

## 🏪 Stores API

### List Stores
```
GET /api/v1/stores
```

### Get Single Store
```
GET /api/v1/stores/{id}
```

### Create Store (Auth Required)
```
POST /api/v1/stores
```

**Body:**
```json
{
  "name": "Main Branch",
  "code": "STORE-001",
  "address": "123 Main St",
  "phone": "0123456789",
  "email": "store@example.com",
  "manager_id": 1,
  "currency": "MMK"
}
```

### Update Store (Auth Required)
```
PUT /api/v1/stores/{id}
```

### Store Inventory (Auth Required)
```
GET /api/v1/stores/{id}/inventory
```

### Store Low Stock (Auth Required)
```
GET /api/v1/stores/{id}/low-stock
```

---

## 💎 Loyalty API

### Get Loyalty Status (Auth Required)
```
GET /api/v1/loyalty/{userId}/status
```

**Response:**
```json
{
  "success": true,
  "data": {
    "points": 500,
    "lifetime_points": 2500,
    "tier": "silver",
    "benefits": {
      "discount": 5,
      "points_multiplier": 1.5,
      "free_shipping": false
    },
    "next_tier": "gold",
    "points_to_next_tier": 2500
  }
}
```

### Get Points Transactions (Auth Required)
```
GET /api/v1/loyalty/{userId}/transactions
```

### Redeem Points (Auth Required)
```
POST /api/v1/loyalty/{userId}/redeem
```

**Body:**
```json
{
  "points": 500
}
```

### Validate Discount Code
```
POST /api/v1/loyalty/validate-code
```

**Body:**
```json
{
  "code": "SAVE20",
  "user_id": 1,
  "cart_total": 1000
}
```

**Response:**
```json
{
  "success": true,
  "discount_amount": 200,
  "discount_code_id": 1,
  "message": "Discount of 200 applied!"
}
```

### List Active Discount Codes
```
GET /api/v1/discount-codes
```

### Create Discount Code (Auth Required)
```
POST /api/v1/discount-codes
```

**Body:**
```json
{
  "name": "Summer Sale",
  "type": "percentage",
  "value": 20,
  "min_purchase": 1000,
  "usage_limit": 100,
  "starts_at": "2025-06-01",
  "expires_at": "2025-08-31"
}
```

---

## 💰 Currency Conversion

### Example Usage
```php
use App\Services\CurrencyService;

$service = new CurrencyService();

// Convert 100 USD to MMK
$mmk = $service->convert(100, 'USD', 'MMK');

// Format currency
$formatted = $service->format(1000, 'USD'); // "$1,000.00"

// Get user's currency
$currency = $service->getUserCurrency();
```

---

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Optional message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Validation error"]
  }
}
```

---

## 🔢 HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## 🚀 Rate Limiting

- **60 requests per minute** for authenticated users
- **30 requests per minute** for guests

---

## 💡 Tips

1. **Always include Content-Type header:**
   ```
   Content-Type: application/json
   ```

2. **Use pagination for large datasets:**
   ```
   ?page=2&per_page=20
   ```

3. **Cache responses when possible**

4. **Use specific fields with `?fields=id,name,price`** (if implemented)

---

## 🧪 Testing

### Using cURL
```bash
# Test public endpoint
curl http://localhost:8000/api/v1/products

# Test authenticated endpoint
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/orders
```

### Using Postman
1. Create new collection
2. Set base URL: `http://localhost:8000/api/v1`
3. Add authorization: Bearer Token
4. Test endpoints

---

## 📱 Integration Examples

### JavaScript/Axios
```javascript
const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  }
});

// Get products
const products = await api.get('/products?low_stock=true');

// Create order
const order = await api.post('/orders', {
  user_id: 1,
  product_id: 1,
  count: 2
});
```

### PHP/Guzzle
```php
$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000/api/v1/',
    'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ]
]);

$response = $client->get('products');
$products = json_decode($response->getBody());
```

---

## 🔧 Setup

1. **Install API support:**
   ```bash
   php artisan install:api
   ```

2. **Publish Sanctum config:**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. **Run migrations:**
   ```bash
   php artisan migrate
   ```

4. **Test:**
   ```bash
   php artisan route:list --path=api
   ```

---

**Full API implementation ready to use!** 🎉
