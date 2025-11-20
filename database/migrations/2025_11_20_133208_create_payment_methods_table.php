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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_gateway_id')->constrained('payment_gateways')->onDelete('cascade');
            $table->string('code')->unique(); // e.g., 'VA', 'BT', 'CC'
            $table->string('name'); // e.g., 'MAYBANK VA', 'PERMATA VA'
            $table->string('image_url')->nullable(); // URL logo dari API
            $table->decimal('fee_merchant_flat', 15, 2)->default(0); // Fee flat dari merchant
            $table->decimal('fee_merchant_percent', 5, 2)->default(0); // Fee percent dari merchant
            $table->decimal('fee_customer_flat', 15, 2)->default(0); // Fee yang dibebankan ke customer (flat)
            $table->decimal('fee_customer_percent', 5, 2)->default(0); // Fee yang dibebankan ke customer (percent)
            $table->decimal('total_fee', 15, 2)->default(0); // Total fee dari API (readonly dari sync)
            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0); // Untuk sorting tampilan
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
