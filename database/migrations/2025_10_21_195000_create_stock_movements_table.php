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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['purchase', 'sale', 'return', 'adjustment', 'transfer', 'damage', 'expired']);
            $table->integer('quantity'); // Positive for in, negative for out
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->string('reference_number')->nullable(); // Order ID, PO number, etc.
            $table->string('reference_type')->nullable(); // 'order', 'purchase_order', 'transfer'
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            
            $table->index(['product_id', 'store_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
