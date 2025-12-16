<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\DB;

final class StoreOrganizationAction
{
    public function __construct() {}

    /**
     * Store an organization
     * @param OrganizationDTO $dto
     * @return array
     */
    public function handle(OrganizationDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $organization = Organization::create([
                'name'    => $dto->name,
                'user_id' => $dto->userId,
            ]);

            OrganizationUser::create([
                'organization_id' => $organization->id,
                'user_id'         => $dto->userId,
                'role'            => 'admin',
            ]);

            return $organization->toArray();
        });
    }
}
