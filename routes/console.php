<?php

use App\Console\Commands\SendMondayNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Define and register the command (optional, auto-loaded)
Artisan::command('notification:monday', function () {
    $this->call(SendMondayNotification::class);
})->purpose('Send a OneSignal notification to users with cars every Monday');

// Schedule the command using cron syntax
Schedule::command('notification:monday')
    ->cron('0 11 * * 1') // Every Monday at 11:00 AM
    ->timezone('CET');   // Central European Time

