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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('alert_type', ['low_stock', 'out_of_stock', 'expiring_soon', 'expired']);
            $table->integer('current_quantity');
            $table->integer('threshold');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['pending', 'acknowledged', 'resolved'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'alert_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
