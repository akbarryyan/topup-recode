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
            $table->enum('margin_type', ['fixed', 'percent'])->default('fixed')->after('price_special');
            $table->unsignedBigInteger('margin_value')->default(0)->after('margin_type');
            $table->unsignedBigInteger('price_basic_original')->nullable()->after('margin_value');
            $table->unsignedBigInteger('price_premium_original')->nullable()->after('price_basic_original');
            $table->unsignedBigInteger('price_special_original')->nullable()->after('price_premium_original');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_services', function (Blueprint $table) {
            $table->dropColumn(['margin_type', 'margin_value', 'price_basic_original', 'price_premium_original', 'price_special_original']);
        });
    }
};
