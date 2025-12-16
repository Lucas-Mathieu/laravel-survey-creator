<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class OrganizationMemberDTO
{
    public function __construct(
        public readonly int $organizationId,
        public readonly int $userId,
        public readonly string $role,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            organizationId: (int) $request->input('organization_id'),
            userId: (int) $request->user()?->id,
            role: (string) $request->input('role', 'member'),
        );
    }
}
