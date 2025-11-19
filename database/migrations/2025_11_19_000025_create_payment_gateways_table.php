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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama gateway (Duitku, Midtrans, Xendit, dll)
            $table->string('code')->unique(); // Kode unik auto-generated (duitku_config, midtrans_config, dll)
            $table->string('merchant_code')->nullable(); // Kode Merchant
            $table->text('api_key')->nullable(); // API Key (akan di-encrypt)
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');
            $table->boolean('is_active')->default(false);
            $table->text('callback_url')->nullable(); // URL callback
            $table->text('return_url')->nullable(); // URL return setelah pembayaran
            $table->string('icon_url')->nullable(); // Path icon yang diupload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
