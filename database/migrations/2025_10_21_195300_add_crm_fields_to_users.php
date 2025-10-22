<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->decimal('total_spent', 10, 2)->default(0)->after('address');
            $table->integer('total_orders')->default(0)->after('total_spent');
            $table->timestamp('last_purchase_at')->nullable()->after('total_orders');
            $table->timestamp('last_login_at')->nullable()->after('last_purchase_at');
            $table->enum('customer_type', ['new', 'repeat', 'vip', 'inactive'])->default('new')->after('last_login_at');
            $table->text('notes')->nullable()->after('customer_type');
            $table->boolean('marketing_consent')->default(false)->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth', 'gender', 'total_spent', 'total_orders',
                'last_purchase_at', 'last_login_at', 'customer_type', 
                'notes', 'marketing_consent'
            ]);
        });
    }
};
