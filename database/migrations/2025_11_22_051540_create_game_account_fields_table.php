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
        Schema::create('game_account_fields', function (Blueprint $table) {
            $table->id();
            $table->string('game_name');
            $table->string('field_key');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->string('input_type')->default('text');
            $table->boolean('is_required')->default(true);
            $table->text('helper_text')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['game_name', 'field_key']);
            $table->index(['game_name', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_account_fields');
    }
};
