<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check game order status every minute for faster updates
Schedule::command('game:check-status')->everyMinute();

// Check prepaid order status every minute for faster updates
Schedule::command('prepaid:check-status')->everyMinute();
