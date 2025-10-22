<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Loyalty Points Table
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->integer('lifetime_points')->default(0);
            $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->timestamps();
        });

        // Points Transactions Table
        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted']);
            $table->integer('points');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('reference_type')->nullable();
            $table->string('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Discount Codes Table
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_shipping']);
            $table->decimal('value', 10, 2);
            $table->decimal('min_purchase', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('per_user_limit')->default(1);
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Discount Usage Table
        Schema::create('discount_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
        });

        // Customer Segments Table
        Schema::create('customer_segments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('criteria')->nullable();
            $table->integer('customer_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Customer Segment Assignments
        Schema::create('customer_segment_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_segment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->unique(['customer_segment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_segment_user');
        Schema::dropIfExists('customer_segments');
        Schema::dropIfExists('discount_usages');
        Schema::dropIfExists('discount_codes');
        Schema::dropIfExists('points_transactions');
        Schema::dropIfExists('loyalty_points');
    }
};
