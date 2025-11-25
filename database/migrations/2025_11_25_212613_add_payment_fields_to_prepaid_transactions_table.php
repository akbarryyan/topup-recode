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
        Schema::table('prepaid_transactions', function (Blueprint $table) {
            // Payment Method
            $table->foreignId('payment_method_id')->nullable()->after('data_no')->constrained('payment_methods')->onDelete('set null');
            $table->string('payment_method_code')->nullable()->after('payment_method_id');
            
            // Payment Amounts
            $table->decimal('payment_amount', 15, 2)->nullable()->after('price'); // Total amount including fees
            $table->decimal('payment_fee', 15, 2)->default(0)->after('payment_amount'); // Payment gateway fee
            
            // Payment Details
            $table->text('payment_url')->nullable()->after('payment_fee'); // URL for payment
            $table->string('payment_reference')->nullable()->after('payment_url')->index(); // Duitku reference
            $table->string('va_number')->nullable()->after('payment_reference'); // Virtual Account number
            $table->text('qr_string')->nullable()->after('va_number'); // QRIS string
            
            // Customer Info
            $table->string('email')->after('data_no');
            $table->string('whatsapp')->nullable()->after('email');
            
            // Payment Status
            $table->enum('payment_status', ['pending', 'paid', 'expired', 'failed'])->default('pending')->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prepaid_transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn([
                'payment_method_id',
                'payment_method_code',
                'payment_amount',
                'payment_fee',
                'payment_url',
                'payment_reference',
                'va_number',
                'qr_string',
                'email',
                'whatsapp',
                'payment_status',
                'paid_at',
                'expired_at',
            ]);
        });
    }
};
