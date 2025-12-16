<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class UpdateOrganizationAction
{
    public function __construct() {}

    /**
     * Update an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function handle(OrganizationDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $organization = Organization::findOrFail($dto->organizationId);
            $organization->update([
                'name'    => $dto->name,
                'user_id' => $dto->userId,
            ]);

            return $organization->toArray();
        });
    }
}
