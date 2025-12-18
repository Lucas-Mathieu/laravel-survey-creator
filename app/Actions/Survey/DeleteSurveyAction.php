<?php
namespace App\Actions\Survey;

use App\Models\Survey;
use Illuminate\Support\Facades\DB;

final class DeleteSurveyAction
{
    public function handle(Survey $survey): Survey
    {
        return DB::transaction(function () use ($survey) {
            $survey->delete();
            return $survey;
        });
    }
}
