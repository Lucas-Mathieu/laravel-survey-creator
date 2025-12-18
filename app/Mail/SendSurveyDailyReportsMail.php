<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSurveyDailyReportsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Survey $survey,
        public int $answersCount,
        public string $reportDate,
    ) {}

    public function build(): self
    {
        return $this->subject('Daily report: '.$this->survey->title.' ('.$this->reportDate.')')
            ->view('emails.send_survey_daily_reports_mail')
            ->with([
                'survey' => $this->survey,
                'answersCount' => $this->answersCount,
                'reportDate' => $this->reportDate,
            ]);
    }
}
