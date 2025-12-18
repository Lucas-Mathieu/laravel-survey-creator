<?php

namespace App\Console\Commands;

use App\Actions\Survey\CloseSurveyAction;
use App\Models\Survey;
use Illuminate\Console\Command;

class CheckForSurveyToClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-for-survey-to-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close surveys whose end_date has passed.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $surveys = Survey::query()
            ->where('end_date', '<=', now())
            ->where('survey_closed', false)
            ->cursor();

        $closeSurveyAction = app(CloseSurveyAction::class);

        foreach ($surveys as $surveyToClose) {
            $closeSurveyAction->handle($surveyToClose);
        }
    }
}
