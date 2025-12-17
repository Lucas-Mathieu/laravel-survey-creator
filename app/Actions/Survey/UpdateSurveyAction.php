<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

final class UpdateSurveyAction
{
    /**
     * Update a Survey
     * @param Survey $survey
     * @param SurveyDTO $dto
     * @return Survey
     */
    public function handle(Survey $survey, SurveyDTO $dto): Survey
    {
        $organizationId = $dto->organizationId ?? $survey->organization_id;

        if (!$organizationId) {
            throw ValidationException::withMessages([
                'organization_id' => 'Organization is required to update a survey.',
            ]);
        }

        if ((int) $survey->organization_id !== (int) $organizationId) {
            throw new AuthorizationException('You cannot update a survey outside your organization.');
        }

        return DB::transaction(function () use ($survey, $dto) {
            $survey->update([
                'title' => $dto->title,
                'description' => $dto->description,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'is_anonymous' => $dto->isAnonymous,
            ]);

            return $survey->refresh();
        });
    }
}
