<?php

namespace App\Listeners;

use App\Events\SurveyAnswerSubmitted;
use App\Mail\NewSurveyAnswerMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewAnswerNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(SurveyAnswerSubmitted $event): void
    {
        $survey = $event->survey;

        if (! $survey->notify_on_answer) {
            return;
        }

        if (! $survey->user) {
            return;
        }

        // Listener is already queued; send the mail directly to avoid double-queuing.
        Mail::to($survey->user->email)->send(new NewSurveyAnswerMail($survey));
    }
}
