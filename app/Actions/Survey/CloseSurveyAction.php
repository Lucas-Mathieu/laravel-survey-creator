<?php

namespace App\Actions\Survey;

use App\Events\SurveyClosed;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

final class CloseSurveyAction
{
    public function handle(Survey $survey): Survey
    {
        return DB::transaction(function () use ($survey) {
            if ($survey->survey_closed) {
                return $survey;
            }

            $survey->forceFill([
                'survey_closed' => true,
            ])->save();

            // event(new SurveyClosed($survey)); // POUR daily report

            return $survey->refresh();
        });
    }
}
