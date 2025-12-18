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
            if (is_array($decoded)) {
                $data = $decoded;
            } else {
                // Fallback: split by new lines or commas
                $parts = preg_split('/[\r\n,]+/', $data);
                $parts = array_values(array_filter(array_map('trim', $parts), fn ($v) => $v !== ''));
                $data = $parts ?: null;
            }
        }

        return new self(
            title: $request->input('title'),
            questionType: $request->input('question_type'),
            data: $data,
            surveyId: (int) ($request->input('survey_id') ?? $request->route('survey')?->id),
        );
    }
}
