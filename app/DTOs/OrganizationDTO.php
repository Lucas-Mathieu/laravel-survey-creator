<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class OrganizationDTO
{
    public function __construct(
        public readonly ?int $organizationId,
        public readonly string $name,
        public readonly int $userId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            organizationId: $request->input('organization_id') ?? $request->input('id'),
            name: (string) $request->input('name', ''),
            userId: (int) ($request->user()?->id ?? $request->input('user_id')),
        );
    }
}
