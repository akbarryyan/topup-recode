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
        Schema::create('top_up_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('merchant_order_id')->unique();
            $table->string('reference')->nullable(); // Duitku reference
            $table->decimal('amount', 15, 2); // Top up amount
            $table->decimal('fee', 15, 2)->default(0); // Admin fee
            $table->decimal('total_amount', 15, 2); // Total including fee
            $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
            $table->string('payment_url')->nullable();
            $table->string('va_number')->nullable(); // Virtual Account number
            $table->text('qr_string')->nullable(); // QRIS string
            $table->enum('status', ['pending', 'paid', 'expired', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('callback_data')->nullable(); // Store callback response
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('merchant_order_id');
            $table->index('reference');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_transactions');
    }
};
