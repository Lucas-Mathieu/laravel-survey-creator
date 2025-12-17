<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\DTOs\SurveyQuestionDTO;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;

final class StoreSurveyQuestionAction
{
    public function handle(SurveyQuestionDTO $dto): SurveyQuestion
    {
        return DB::transaction(function () use ($dto) {
            // Ensure 'options' is an array (DB column is non-nullable json)
            $options = null;
            if ($dto->options !== null) {
                $options = json_decode($dto->options, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Fallback: keep it as an empty array
                    $options = [];
                }
            } else {
                $options = [];
            }

            return SurveyQuestion::create([
                'title' => $dto->title,
                'question_type' => $dto->questionType,
                'options' => $options,
                'survey_id' => $dto->surveyId,
            ]);
        });
    }
}
