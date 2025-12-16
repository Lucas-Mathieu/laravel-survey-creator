<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyDTO
{
    private function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly bool $isAnonymous,
        public readonly int $userId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            isAnonymous: $request->boolean('is_anonymous'),
            userId: $request->user()->id,
        );
    }
}
