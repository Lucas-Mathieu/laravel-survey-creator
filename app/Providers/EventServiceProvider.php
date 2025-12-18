<?php

namespace App\Providers;

use App\Events\SurveyAnswerSubmitted;
use App\Listeners\SendNewAnswerNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\SendDailyReport;
use App\Events\DailyAnswersThresholdReached;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SurveyAnswerSubmitted::class => [
            SendNewAnswerNotification::class,
        ],
        DailyAnswersThresholdReached::class => [
            SendDailyReport::class,
        ],
    ];
}
