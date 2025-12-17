<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyQuestionDTO;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;

final class StoreSurveyQuestionAction
{
    public function handle(SurveyQuestionDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $question = SurveyQuestion::create([
                'title' => $dto->title,
                'question_type' => $dto->questionType,
                'data' => $dto->data,
                'survey_id' => $dto->surveyId,
            ]);

            return $question->toArray();
        });
    }
}
