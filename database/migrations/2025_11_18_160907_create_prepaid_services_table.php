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
        Schema::create('prepaid_services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('brand')->nullable();
            $table->string('name');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('price_basic');
            $table->unsignedBigInteger('price_premium');
            $table->unsignedBigInteger('price_special');
            $table->unsignedBigInteger('price_basic_original');
            $table->unsignedBigInteger('price_premium_original');
            $table->unsignedBigInteger('price_special_original');
            $table->enum('margin_type', ['fixed', 'percent'])->default('fixed');
            $table->unsignedBigInteger('margin_value')->default(0);
            $table->integer('stock')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('stock_updated_at')->nullable();
            $table->boolean('multi_trx')->default(false);
            $table->string('maintenance')->nullable();
            $table->string('category')->nullable();
            $table->string('prepost')->default('prepaid');
            $table->string('type')->nullable();
            $table->enum('status', ['available', 'empty'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_services');
    }
};
