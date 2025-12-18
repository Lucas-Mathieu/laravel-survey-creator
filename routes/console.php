<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CheckForSurveyToClose;
use App\Console\Commands\SendSurveyDailyReports;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(CheckForSurveyToClose::class)->dailyAt('00:01');
Schedule::command(SendSurveyDailyReports::class)->dailyAt('8:00');
