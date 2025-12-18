<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSurveyAnswerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Survey $survey)
    {
    }

    public function build(): self
    {
        return $this->subject('New survey response: '.$this->survey->title)
            ->view('emails.new_survey_answer')
            ->with([
                'survey' => $this->survey,
            ]);
    }
}
