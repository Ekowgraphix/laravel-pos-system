<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Inventory Management
            $table->integer('low_stock_threshold')->default(10)->after('count');
            $table->string('batch_number')->nullable()->after('low_stock_threshold');
            $table->date('expiry_date')->nullable()->after('batch_number');
            $table->integer('reorder_level')->default(20)->after('expiry_date');
            $table->integer('reorder_quantity')->default(50)->after('reorder_level');
            
            // Multi-store support
            $table->foreignId('store_id')->nullable()->after('category_id');
            $table->string('barcode')->unique()->nullable()->after('store_id');
            $table->string('sku')->unique()->nullable()->after('barcode');
            
            // Supplier information
            $table->string('supplier_name')->nullable()->after('sku');
            $table->string('supplier_contact')->nullable()->after('supplier_name');
            
            // Stock tracking
            $table->timestamp('last_restocked_at')->nullable()->after('supplier_contact');
            $table->integer('total_sold')->default(0)->after('last_restocked_at');
            
            // Alerts
            $table->boolean('alert_enabled')->default(true)->after('total_sold');
            $table->timestamp('last_alert_sent_at')->nullable()->after('alert_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'low_stock_threshold',
                'batch_number',
                'expiry_date',
                'reorder_level',
                'reorder_quantity',
                'store_id',
                'barcode',
                'sku',
                'supplier_name',
                'supplier_contact',
                'last_restocked_at',
                'total_sold',
                'alert_enabled',
                'last_alert_sent_at',
            ]);
        });
    }
};
