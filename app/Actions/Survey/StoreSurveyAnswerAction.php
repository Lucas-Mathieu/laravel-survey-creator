<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyAnswerDTO;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StoreSurveyAnswerAction
{
    public function __construct() {}

    /**
     * Store survey answers
     * @param SurveyAnswerDTO $dto
     * @return array
     */
    public function handle(SurveyAnswerDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $survey = Survey::findOrFail($dto->surveyId);

            $questionIds = SurveyQuestion::where('survey_id', $survey->id)
                ->pluck('id')
                ->all();

            $questionIdSet = array_flip($questionIds);

            $created = [];
            foreach ($dto->answers as $answer) {
                $questionId = (int) ($answer['question_id'] ?? 0);
                $value = (string) ($answer['answer'] ?? '');

                if (! isset($questionIdSet[$questionId])) {
                    throw ValidationException::withMessages([
                        'answers' => 'One or more questions do not belong to this survey.',
                    ]);
                }

                $created[] = SurveyAnswer::create([
                    'survey_id' => $survey->id,
                    'survey_question_id' => $questionId,
                    'user_id' => $survey->is_anonymous ? null : $dto->userId,
                    'answer' => $value,
                ])->toArray();
            }

            return $created;
        });
    }
}
