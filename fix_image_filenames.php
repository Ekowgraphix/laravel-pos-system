<?php

/**
 * Script to Fix Product Image Filenames
 * Renames files with spaces and special characters to clean filenames
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "🔧 Starting Image Filename Cleanup...\n\n";

$products = Product::whereNotNull('image')->get();
$fixed = 0;
$errors = 0;

foreach ($products as $product) {
    $oldFileName = $product->image;
    $oldPath = public_path('productImages/' . $oldFileName);
    
    // Check if filename has problematic characters
    if (preg_match('/[\s#%&{}\\<>*?\/\$!\'":@+`|=]/', $oldFileName)) {
        echo "📁 Processing: {$product->name}\n";
        echo "   Old filename: {$oldFileName}\n";
        
        // Check if old file exists
        if (file_exists($oldPath)) {
            // Get extension
            $extension = pathinfo($oldFileName, PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = 'jpg'; // Default to jpg if no extension
            }
            
            // Create new clean filename
            $newFileName = uniqid() . '_' . time() . '.' . $extension;
            $newPath = public_path('productImages/' . $newFileName);
            
            // Rename the file
            if (rename($oldPath, $newPath)) {
                // Update database
                $product->image = $newFileName;
                $product->save();
                
                echo "   ✅ New filename: {$newFileName}\n";
                echo "   ✅ File renamed successfully\n\n";
                $fixed++;
            } else {
                echo "   ❌ Failed to rename file\n\n";
                $errors++;
            }
        } else {
            echo "   ⚠️  File not found: {$oldPath}\n";
            echo "   ℹ️  You may need to re-upload this image\n\n";
            $errors++;
        }
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 Summary:\n";
echo "   ✅ Files fixed: {$fixed}\n";
echo "   ❌ Errors: {$errors}\n";
echo "   📁 Total products checked: " . $products->count() . "\n";
echo str_repeat("=", 50) . "\n\n";

if ($fixed > 0) {
    echo "🎉 Cleanup completed! Run your application and check if images display correctly.\n";
} else {
    echo "✨ No files needed fixing. All filenames are already clean!\n";
}

echo "\n";
