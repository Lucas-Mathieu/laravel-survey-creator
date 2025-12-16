<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;

final class StoreSurveyQuestionAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyDTO $dto
     * @return array
     */
    public function handle(SurveyQuestionDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            // Logic to store survey question goes here
            // This is a placeholder return statement
            return [
                'title' => $dto->title,
                'question_type' => $dto->questionType,
                'options' => $dto->options,
            ];
        });
    }
}
