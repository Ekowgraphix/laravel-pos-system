<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // USD, EUR, MMK, etc.
            $table->string('name');
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 15, 6)->default(1.000000); // Rate to base currency
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('decimal_places')->default(2);
            $table->timestamps();
        });

        Schema::create('exchange_rate_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->decimal('rate', 15, 6);
            $table->date('effective_date');
            $table->timestamps();
        });

        // Add currency field to orders if not exists
        if (!Schema::hasColumn('orders', 'currency')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('currency', 3)->default('MMK')->after('totalPrice');
                $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('currency');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['currency', 'exchange_rate']);
        });
        Schema::dropIfExists('exchange_rate_history');
        Schema::dropIfExists('currencies');
    }
};
