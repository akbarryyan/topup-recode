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
        Schema::create('game_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trxid')->unique()->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service_code'); // kode layanan
            $table->string('service_name'); // nama layanan
            $table->string('data_no'); // ID tujuan
            $table->string('data_zone')->nullable(); // Zone tujuan (optional)
            $table->enum('status', ['waiting', 'processing', 'success', 'failed'])->default('waiting');
            $table->decimal('price', 15, 2); // harga transaksi
            $table->decimal('balance', 15, 2); // sisa saldo setelah transaksi
            $table->text('note')->nullable(); // catatan dari API
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_transactions');
    }
};
