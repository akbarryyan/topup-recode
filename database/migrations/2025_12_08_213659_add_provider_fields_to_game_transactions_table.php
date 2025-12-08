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
            // Provider (VIP Reseller) response fields
            $table->string('provider_trxid')->nullable()->after('note')->comment('Transaction ID from VIP Reseller');
            $table->string('provider_status')->nullable()->after('provider_trxid')->comment('Status from VIP Reseller (waiting/processing/success/failed)');
            $table->text('provider_note')->nullable()->after('provider_status')->comment('Note/message from VIP Reseller');
            $table->decimal('provider_price', 15, 2)->nullable()->after('provider_note')->comment('Price charged by VIP Reseller');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'provider_trxid',
                'provider_status',
                'provider_note',
                'provider_price',
            ]);
        });
    }
};