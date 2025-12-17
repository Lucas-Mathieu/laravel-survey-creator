<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;

final class StoreSurveyQuestionAction
{
    public function handle(SurveyQuestionDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            return [
                'title' => $dto->title,
                'question_type' => $dto->questionType,
                'options' => $dto->options,
                'survey_id' => $dto->surveyId,
            ];
        });
    }
}
