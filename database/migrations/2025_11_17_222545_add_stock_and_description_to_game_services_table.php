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
            $table->integer('stock')->nullable()->after('status');
            $table->text('description')->nullable()->after('name');
            $table->timestamp('stock_updated_at')->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_services', function (Blueprint $table) {
            $table->dropColumn(['stock', 'description', 'stock_updated_at']);
        });
    }
};
