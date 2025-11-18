<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ML14-S14
            $table->string('game'); // Mobile Legends
            $table->string('name'); // 14 Diamonds
            $table->integer('price_basic');
            $table->integer('price_premium');
            $table->integer('price_special');
            $table->string('server')->default('1');
            $table->enum('status', ['available', 'empty'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('game');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_services');
    }
};