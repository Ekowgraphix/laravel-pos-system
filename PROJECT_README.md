# 🛒 Laravel POS System

A comprehensive **Point of Sale (POS) System** built with Laravel 11, designed for managing sales, inventory, orders, and customer interactions in retail businesses.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat&logo=php)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=flat&logo=tailwind-css)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## 📋 Table of Contents

- [About](#-about)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [User Roles](#-user-roles)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## 📖 About

This Laravel POS System is a full-featured web application designed to help businesses manage their sales operations efficiently. It includes inventory management, order processing, payment tracking, customer management, and comprehensive sales reporting.

**Created by:** [Join Coder](https://www.youtube.com/@joincoder)  
**Purpose:** Educational and commercial use  
**Status:** Open Source

---

## ✨ Features

### 🔐 Authentication & Authorization
- Multi-level user authentication (SuperAdmin, Admin, User)
- Social login with Google and GitHub (OAuth)
- Role-based access control
- Secure password management and reset functionality

### 👨‍💼 Admin Features
- **Dashboard**: Real-time sales analytics and business metrics
- **Product Management**: 
  - Create, edit, delete, and view products
  - Product images upload
  - Stock quantity tracking
  - Purchase price and selling price management
- **Category Management**: Organize products by categories
- **Payment Methods**: Configure multiple payment options (KBZPay, WPay, etc.)
- **Order Management**:
  - View all customer orders
  - Update order status (Pending, Processing, Completed, Rejected)
  - Track order history and payment slips
- **User Management**:
  - Admin account creation
  - User role management (promote/demote users)
  - Account deletion and suspension
- **Sales Reports**:
  - Sales report by date range
  - Product-wise sales analysis
  - Profit/Loss reports
  - Downloadable reports

### 🛍️ Customer/User Features
- **Product Browsing**:
  - Browse products by category
  - Detailed product views
  - Product search and filtering
- **Shopping Cart**:
  - Add/remove items from cart
  - Quantity management
  - Real-time price calculation
- **Order Processing**:
  - Place orders with multiple items
  - Upload payment slip images
  - View order history and status
- **Reviews & Ratings**:
  - Rate products (1-5 stars)
  - Write product reviews/comments
- **Profile Management**:
  - Update personal information
  - Change password
  - Upload profile picture
- **Contact Form**: Send messages to admin

### 📊 Reporting & Analytics
- Daily, weekly, and monthly sales reports
- Product performance tracking
- Profit margin analysis
- Order status distribution
- Customer purchase history

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: SQLite / MySQL (configurable)
- **Authentication**: Laravel Breeze + Socialite

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js
- **Build Tool**: Vite 5.x
- **Icons**: Lucide Icons
- **Alerts**: SweetAlert2

### Additional Packages
- `laravel/socialite` - OAuth authentication
- `realrashid/sweet-alert` - Beautiful alerts

---

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite or MySQL (optional)

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd "Laravel POS(SourceCode)"
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

**Option A: Using SQLite (Recommended for quick start)**
```bash
# Create SQLite database
touch database/database.sqlite

# Update .env file
DB_CONNECTION=sqlite
# Comment out MySQL settings
```

**Option B: Using MySQL**
```bash
# Create database
mysql -u root -p
CREATE DATABASE mypos;

# Update .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mypos
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations & Seed Data
```bash
# Run migrations and seeders
php artisan migrate --seed

# OR import sample data from SQL file (MySQL only)
# Import database/mypos.sql via phpMyAdmin
```

### Step 6: Storage Link
```bash
# Create symbolic link for storage
php artisan storage:link
```

### Step 7: Start Development Servers
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Step 8: Access the Application
Open your browser and visit:
- **URL**: http://127.0.0.1:8000
- **SuperAdmin Login**:
  - Email: `superadmin@gmail.com`
  - Password: `admin123`

---

## ⚙️ Configuration

### Social Login Setup (Optional)

To enable Google and GitHub login:

1. **Google OAuth**:
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create OAuth 2.0 credentials
   - Add to `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

2. **GitHub OAuth**:
   - Go to [GitHub Developer Settings](https://github.com/settings/developers)
   - Create OAuth App
   - Add to `.env`:
   ```env
   GITHUB_CLIENT_ID=your_client_id
   GITHUB_CLIENT_SECRET=your_client_secret
   GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
   ```

---

## 📱 Usage

### Default Accounts

| Role | Email | Password |
|------|-------|----------|
| SuperAdmin | superadmin@gmail.com | admin123 |
| Admin | admin@gmail.com | admin123 |

### Admin Workflow
1. Login as SuperAdmin/Admin
2. Add product categories (Anniversary, Wedding, Birthday, etc.)
3. Add products with images, prices, and stock
4. Configure payment methods
5. Monitor incoming orders from customers
6. Update order status (approve/reject orders)
7. Generate sales reports

### Customer Workflow
1. Register/Login as customer
2. Browse products by category
3. View product details and reviews
4. Add products to cart
5. Proceed to checkout
6. Upload payment slip
7. Track order status

---

## 👥 User Roles

### 🔴 SuperAdmin
- Full system access
- Create/delete admin accounts
- Manage user roles
- Access all reports
- System configuration

### 🟠 Admin
- Manage products and categories
- Process orders
- View sales reports
- Manage payment methods
- Cannot create other admins

### 🟢 User (Customer)
- Browse and purchase products
- Manage shopping cart
- Track orders
- Write reviews and ratings
- Manage profile

---

## 📂 Project Structure

```
Laravel POS(SourceCode)/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   └── User/           # Customer controllers
│   ├── Models/             # Eloquent models
│   └── Middleware/         # Custom middleware
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/           # Database seeders
│   └── mypos.sql          # Sample data (MySQL)
├── public/                # Public assets
├── resources/
│   ├── views/             # Blade templates
│   └── js/                # JavaScript files
├── routes/
│   ├── web.php           # Web routes
│   ├── admin.php         # Admin routes
│   └── user.php          # Customer routes
└── storage/              # File storage
```

---

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts and authentication
- **categories** - Product categories
- **products** - Product inventory
- **carts** - Shopping cart items
- **orders** - Customer orders
- **payments** - Payment methods
- **pay_slip_histories** - Payment slip uploads
- **ratings** - Product ratings
- **comments** - Product reviews
- **reports** - System reports

---

## 🎨 Screenshots

### Admin Dashboard
- Sales analytics and charts
- Recent orders overview
- Quick actions panel

### Product Management
- Product listing with images
- Add/Edit product forms
- Stock management

### Order Management
- Order list with filters
- Order details with payment slips
- Status update interface

### Customer Shop
- Product catalog
- Shopping cart
- Checkout process

---

## 🤝 Contributing

Contributions are welcome! This project is shared freely for learning purposes.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 🐛 Known Issues

- Some unnecessary template files may be included
- Image uploads require proper storage configuration
- Payment slip validation is basic

---

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

**Attribution**: If you use this project, please give credit to [Join Coder](https://www.youtube.com/@joincoder).

---

## 📞 Support

- **YouTube**: [@joincoder](https://www.youtube.com/@joincoder)
- **Issues**: Create an issue on GitHub
- **Email**: Contact through the application's contact form

---

## 🔄 Updates & Roadmap

### Planned Features
- [ ] Multi-currency support
- [ ] Invoice generation (PDF)
- [ ] Email notifications
- [ ] SMS integration
- [ ] Inventory alerts
- [ ] Barcode scanning
- [ ] Advanced analytics dashboard
- [ ] Export reports to Excel

---

## 🙏 Acknowledgments

- **Laravel Community** - For the amazing framework
- **Tailwind CSS** - For the utility-first CSS framework
- **Join Coder** - Original author and creator
- **Free UI Template** - Base template used in this project

---

## 📊 Database Models

```php
User        → hasMany(Order, Cart, Comment, Rating)
Category    → hasMany(Product)
Product     → belongsTo(Category) + hasMany(Order, Cart, Comment, Rating)
Order       → belongsTo(User, Product) + hasOne(PaySlipHistory)
Payment     → hasMany(PaySlipHistory)
Cart        → belongsTo(User, Product)
```

---

**Made with ❤️ by Join Coder**

*Happy Coding! 🚀*
