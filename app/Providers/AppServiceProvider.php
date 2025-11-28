<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register morph map for polymorphic relationships
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'prepaid_transaction' => \App\Models\PrepaidTransaction::class,
            'game_transaction' => \App\Models\GameTransaction::class,
            'topup_transaction' => \App\Models\TopupTransaction::class,
        ]);
    }
}
