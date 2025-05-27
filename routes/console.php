<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('schedule:run', function (Schedule $schedule) {
    $schedule->command('email:send-user-log')->everyMinute();
    $schedule->command('cache:clear-email-cache')->dailyAt('23:59');
});