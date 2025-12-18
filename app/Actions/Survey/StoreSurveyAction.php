<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use App\Models\Survey;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StoreSurveyAction
{
    public function handle(SurveyDTO $dto): Survey
    {
        $organizationId = $dto->organizationId
            ?? OrganizationUser::where('user_id', $dto->userId)->value('organization_id');

        if (!$organizationId) {
            throw ValidationException::withMessages([
                'organization_id' => 'Organization is required to create a survey.',
            ]);
        }

        return DB::transaction(function () use ($dto, $organizationId) {
            return Survey::create([
                'title' => $dto->title,
                'description' => $dto->description,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'is_anonymous' => $dto->isAnonymous,
                'notify_on_answer' => $dto->notifyOnAnswer,
                'user_id' => $dto->userId,
                'organization_id' => $organizationId,
            ]);
        });
    }
}
