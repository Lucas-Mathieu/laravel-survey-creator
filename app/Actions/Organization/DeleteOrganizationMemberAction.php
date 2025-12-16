<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\DTOs\OrganizationMemberDTO;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\DB;

final class DeleteOrganizationMemberAction
{
    public function __construct() {}

    /**
     * Delete an organization member
     * @param OrganizationMemberDTO $dto
     * @return array
     * @throws \Throwable
     */
    public function handle(OrganizationMemberDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $organizationUser = OrganizationUser::where('organization_id', $dto->organizationId)
                ->where('user_id', $dto->userId)
                ->firstOrFail();
            $organizationUser->delete();

            return $organizationUser->toArray();
        });
    }
}
