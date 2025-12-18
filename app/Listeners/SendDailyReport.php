<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendSurveyDailyReportsMail;
use App\Events\DailyAnswersThresholdReached;

class SendDailyReport
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DailyAnswersThresholdReached $event): void
    {
        $survey = $event->survey;

        if (! $survey->user) {
            return;
        }

        Mail::to($survey->user->email)->queue(
            new SendSurveyDailyReportsMail(
                survey: $survey,
                answersCount: $event->answersCount,
                reportDate: $event->reportDate,
            )
        );
    }
}
