<?php

use App\Models\Attendance;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('attendance:remind-checkout')
    ->dailyAt(sprintf('%02d:00', Attendance::CHECK_OUT_AVAILABLE_FROM_HOUR));
