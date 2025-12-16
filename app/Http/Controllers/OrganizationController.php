<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Http\Controllers\Controller;

use App\Http\Requests\Organization\StoreOrganization;
use App\Http\Requests\Organization\UpdateOrganization;
use App\Http\Requests\Organization\DeleteOrganization;
use App\Http\Requests\Organization\StoreOrganizationMember;
use App\Http\Requests\Organization\DeleteOrganizationMember;

use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationMemberAction;
use App\Actions\Organization\DeleteOrganizationMemberAction;

use App\DTOs\OrganizationDTO;
use App\DTOs\OrganizationMemberDTO;
use App\Models\User;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $organizations = Organization::whereIn(
            'id',
            OrganizationUser::where('user_id', $request->user()->id)->pluck('organization_id')
        )->get();

        return view('organizations', [
            'organizations' => $organizations,
        ]);
    }

    public function create(StoreOrganization $request, StoreOrganizationAction $action)
    {
        $dto = OrganizationDTO::fromRequest($request);
        $organization = $action->handle($dto);

        return response()->json([
            'data' => $organization,
            'message' => 'organization created successfully',
        ], 201);
    }

    public function update(UpdateOrganization $request, Organization $organization, UpdateOrganizationAction $action)
    {
        $dto = new OrganizationDTO(
            organizationId: $organization->id,
            name: $request->validated()['name'],
            userId: $request->user()->id,
        );

        $updated = $action->handle($dto);

        return response()->json([
            'data' => $updated,
            'message' => 'organization updated successfully',
        ]);
    }

    public function destroy(DeleteOrganization $request, Organization $organization, DeleteOrganizationAction $action)
    {
        $dto = new OrganizationDTO(
            organizationId: $organization->id,
            name: $organization->name,
            userId: $request->user()->id,
        );

        $deleted = $action->handle($dto);

        return response()->json([
            'data' => $deleted,
            'message' => 'organization deleted successfully',
        ]);
    }

    public function storeMember(StoreOrganizationMember $request, Organization $organization, StoreOrganizationMemberAction $action)
    {
        $dto = new OrganizationMemberDTO(
            organizationId: $organization->id,
            userId: (int) $request->input('user_id'),
            role: (string) $request->input('role', 'member'),
        );

        $member = $action->handle($dto);

        return response()->json([
            'data' => $member,
            'message' => 'member added successfully',
        ], 201);
    }

    public function destroyMember(DeleteOrganizationMember $request, Organization $organization, User $user, DeleteOrganizationMemberAction $action)
    {
        $dto = new OrganizationMemberDTO(
            organizationId: $organization->id,
            userId: $request->input('user_id') ?? $user->id,
            role: 'member',
        );

        $deleted = $action->handle($dto);

        return response()->json([
            'data' => $deleted,
            'message' => 'member removed successfully',
        ]);
    }
}
