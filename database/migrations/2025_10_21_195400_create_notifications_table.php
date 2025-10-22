<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['notifiable_type', 'notifiable_id']);
        });

        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_low_stock')->default(true);
            $table->boolean('email_new_order')->default(true);
            $table->boolean('email_order_status')->default(true);
            $table->boolean('email_payment')->default(true);
            $table->boolean('sms_low_stock')->default(false);
            $table->boolean('sms_new_order')->default(false);
            $table->boolean('push_notifications')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
        Schema::dropIfExists('notifications');
    }
};
