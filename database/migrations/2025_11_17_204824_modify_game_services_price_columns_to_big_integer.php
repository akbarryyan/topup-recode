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
        Schema::table('game_services', function (Blueprint $table) {
            $table->unsignedBigInteger('price_basic')->change();
            $table->unsignedBigInteger('price_premium')->change();
            $table->unsignedBigInteger('price_special')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_services', function (Blueprint $table) {
            $table->integer('price_basic')->change();
            $table->integer('price_premium')->change();
            $table->integer('price_special')->change();
        });
    }
};
