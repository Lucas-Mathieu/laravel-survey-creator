<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller
{
    use AuthorizesRequests;

    // Ensure an active organization is present in session for org-scoped flows.
    protected function ensureActiveOrganization(Request $request): ?int
    {
        $orgIds = OrganizationUser::where('user_id', $request->user()->id)
            ->pluck('organization_id');

        $activeOrgId = $request->session()->get('active_organization_id');

        if (! $activeOrgId && $orgIds->isNotEmpty()) {
            $activeOrgId = $orgIds->first();
            $request->session()->put('active_organization_id', $activeOrgId);
        }

        return $activeOrgId;
    }
}
