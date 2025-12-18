<?php

namespace App\Console\Commands;

use App\Models\SurveyAnswer;
use Illuminate\Console\Command;
use App\Events\DailyAnswersThresholdReached;
use App\Models\Survey;

class SendSurveyDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-survey-daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App sends daily survey reports to administrators for minimum 10 response.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reportDate = now()->subDay()->toDateString();

        $answersPerSurvey = SurveyAnswer::query()
            ->selectRaw('survey_id, COUNT(*) as answers_count')
            ->whereDate('created_at', $reportDate)
            ->groupBy('survey_id')
            ->having('answers_count', '>=', 10)
            ->get();

        foreach ($answersPerSurvey as $row) {
            $survey = Survey::with('user')->find($row->survey_id);

            if (! $survey || ! $survey->user) {
                continue;
            }

            event(new DailyAnswersThresholdReached($survey, (int) $row->answers_count, $reportDate));
        }

    }
}
