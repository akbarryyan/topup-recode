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
        Schema::table('game_transactions', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_code')->nullable();
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->decimal('payment_fee', 15, 2)->default(0);
            $table->string('payment_url')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('va_number')->nullable();
            $table->text('qr_string')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, expired, failed
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_transactions', function (Blueprint $table) {
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
