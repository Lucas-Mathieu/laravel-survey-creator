<?php

namespace App\DTOs;

use App\Http\Requests\Survey\StoreSurveyQuestionRequest;

final class SurveyQuestionDTO
{
    private function __construct(
        public readonly string $title,
        public readonly string $questionType,
        public readonly ?array $data,
        public readonly int $surveyId,
    ) {}

    public static function fromRequest(StoreSurveyQuestionRequest $request): self
    {
        $data = $request->input('data');

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = is_array($decoded) ? $decoded : null;
        }

        return new self(
            title: $request->input('title'),
            questionType: $request->input('question_type'),
            data: $data,
            surveyId: $request->input('survey_id'),
        );
    }
}
