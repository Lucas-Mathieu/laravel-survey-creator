<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyAnswerDTO
{
    public function __construct(
        public readonly int $surveyId,
        public readonly ?int $userId,
        /** @var array<int, array{question_id:int, answer:string}> */
        public readonly array $answers,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            surveyId: (int) $request->input('survey_id'),
            userId: $request->user()?->id,
            answers: $request->input('answers', []),
        );
    }
}
