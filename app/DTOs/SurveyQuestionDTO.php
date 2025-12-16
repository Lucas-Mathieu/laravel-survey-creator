<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    private function __construct(
        public readonly string $title,
        public readonly string $questionType,
        public readonly ?string $options,
    ) {}

    public static function fromRequest(StoreQuestionRequest $request): self
    {
        return new self(
            title: $request->input('title'),
            questionType: $request->input('question_type'),
            options: $request->input('options'),
        );
    }
}
