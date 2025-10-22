<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Food & Beverages'],
            ['name' => 'Books'],
            ['name' => 'Home & Garden'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        // Get category IDs
        $electronics = Category::where('name', 'Electronics')->first()->id;
        $clothing = Category::where('name', 'Clothing')->first()->id;
        $food = Category::where('name', 'Food & Beverages')->first()->id;
        $books = Category::where('name', 'Books')->first()->id;
        $home = Category::where('name', 'Home & Garden')->first()->id;

        // Create Sample Products
        $products = [
            // Electronics
            [
                'name' => 'Wireless Bluetooth Headphones',
                'price' => 15000, // GH₵ 150.00
                'purchase_price' => 10000,
                'category_id' => $electronics,
                'description' => 'High-quality wireless headphones with noise cancellation and 20-hour battery life.',
                'count' => 50,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Smart Watch',
                'price' => 25000, // GH₵ 250.00
                'purchase_price' => 18000,
                'category_id' => $electronics,
                'description' => 'Fitness tracking smart watch with heart rate monitor and GPS.',
                'count' => 30,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'USB-C Charging Cable',
                'price' => 2500, // GH₵ 25.00
                'purchase_price' => 1500,
                'category_id' => $electronics,
                'description' => 'Fast charging USB-C cable, 2 meters long.',
                'count' => 100,
                'image' => 'defaultImg/default.jpg',
            ],

            // Clothing
            [
                'name' => 'Cotton T-Shirt',
                'price' => 5000, // GH₵ 50.00
                'purchase_price' => 3000,
                'category_id' => $clothing,
                'description' => '100% cotton, comfortable fit, available in multiple colors.',
                'count' => 75,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Denim Jeans',
                'price' => 12000, // GH₵ 120.00
                'purchase_price' => 8000,
                'category_id' => $clothing,
                'description' => 'Classic fit denim jeans, durable and stylish.',
                'count' => 40,
                'image' => 'defaultImg/default.jpg',
            ],

            // Food & Beverages
            [
                'name' => 'Organic Green Tea (50 bags)',
                'price' => 3500, // GH₵ 35.00
                'purchase_price' => 2000,
                'category_id' => $food,
                'description' => 'Premium organic green tea, rich in antioxidants.',
                'count' => 60,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Dark Chocolate Bar',
                'price' => 1500, // GH₵ 15.00
                'purchase_price' => 800,
                'category_id' => $food,
                'description' => '70% cocoa dark chocolate, 100g bar.',
                'count' => 120,
                'image' => 'defaultImg/default.jpg',
            ],

            // Books
            [
                'name' => 'The Art of Programming',
                'price' => 8000, // GH₵ 80.00
                'purchase_price' => 5000,
                'category_id' => $books,
                'description' => 'Comprehensive guide to modern programming practices.',
                'count' => 25,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Business Management 101',
                'price' => 6500, // GH₵ 65.00
                'purchase_price' => 4000,
                'category_id' => $books,
                'description' => 'Essential principles of business management.',
                'count' => 35,
                'image' => 'defaultImg/default.jpg',
            ],

            // Home & Garden
            [
                'name' => 'LED Desk Lamp',
                'price' => 7500, // GH₵ 75.00
                'purchase_price' => 5000,
                'category_id' => $home,
                'description' => 'Adjustable LED desk lamp with multiple brightness levels.',
                'count' => 45,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Indoor Plant Pot Set (3pc)',
                'price' => 4500, // GH₵ 45.00
                'purchase_price' => 2500,
                'category_id' => $home,
                'description' => 'Decorative ceramic plant pots, set of 3 different sizes.',
                'count' => 50,
                'image' => 'defaultImg/default.jpg',
            ],
            [
                'name' => 'Kitchen Knife Set',
                'price' => 9500, // GH₵ 95.00
                'purchase_price' => 6000,
                'category_id' => $home,
                'description' => 'Professional 5-piece stainless steel knife set.',
                'count' => 30,
                'image' => 'defaultImg/default.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }

        $this->command->info('✓ Created ' . count($categories) . ' categories');
        $this->command->info('✓ Created ' . count($products) . ' products');
        $this->command->info('Sample products ready for testing!');
    }
}
