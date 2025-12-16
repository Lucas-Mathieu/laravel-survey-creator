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

        $organizationMembers = OrganizationUser::with('user')
            ->whereIn('organization_id', $organizations->pluck('id'))
            ->get()
            ->groupBy('organization_id');

        if (!$request->session()->has('active_organization_id') && $organizations->count() > 0) {
            $request->session()->put('active_organization_id', $organizations->first()->id);
        }

        return view('organizations', [
            'organizations' => $organizations,
            'users' => User::all(),
            'organizationMembers' => $organizationMembers,
            'activeOrganizationId' => $request->session()->get('active_organization_id'),
        ]);
    }

    public function create(StoreOrganization $request, StoreOrganizationAction $action)
    {
        $dto = OrganizationDTO::fromRequest($request);
        $organization = $action->handle($dto);

        $request->session()->put('active_organization_id', $organization['id']);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Organization created successfully.');
    }

    public function update(UpdateOrganization $request, Organization $organization, UpdateOrganizationAction $action)
    {
        if ((int) $request->session()->get('active_organization_id') !== (int) $organization->id) {
            return redirect()
                ->route('organizations.index')
                ->withErrors(['organization_id' => 'You must switch to this organization to update it.']);
        }

        $dto = new OrganizationDTO(
            organizationId: $organization->id,
            name: $request->validated()['name'],
            userId: $request->user()->id,
        );

        $updated = $action->handle($dto);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Organization updated successfully.');
    }

    public function destroy(DeleteOrganization $request, Organization $organization, DeleteOrganizationAction $action)
    {
        if ((int) $request->session()->get('active_organization_id') !== (int) $organization->id) {
            return redirect()
                ->route('organizations.index')
                ->withErrors(['organization_id' => 'You must switch to this organization to delete it.']);
        }

        $dto = new OrganizationDTO(
            organizationId: $organization->id,
            name: $organization->name,
            userId: $request->user()->id,
        );

        $deleted = $action->handle($dto);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Organization deleted successfully.');
    }

    public function storeMember(StoreOrganizationMember $request, Organization $organization, StoreOrganizationMemberAction $action)
    {
        if ((int) $request->session()->get('active_organization_id') !== (int) $organization->id) {
            return redirect()
                ->route('organizations.index')
                ->withErrors(['organization_id' => 'You must switch to this organization to add members.']);
        }

        $dto = new OrganizationMemberDTO(
            organizationId: $organization->id,
            userId: (int) $request->input('user_id'),
            role: (string) $request->input('role', 'member'),
        );

        $member = $action->handle($dto);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Member added successfully.');
    }

    public function destroyMember(DeleteOrganizationMember $request, Organization $organization, User $user, DeleteOrganizationMemberAction $action)
    {
        if ((int) $request->session()->get('active_organization_id') !== (int) $organization->id) {
            return redirect()
                ->route('organizations.index')
                ->withErrors(['organization_id' => 'You must switch to this organization to remove members.']);
        }

        $dto = new OrganizationMemberDTO(
            organizationId: $organization->id,
            userId: $request->input('user_id') ?? $user->id,
            role: 'member',
        );

        $deleted = $action->handle($dto);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Member removed successfully.');
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
        ]);

        $orgId = (int) $request->input('organization_id');

        $belongs = OrganizationUser::where('organization_id', $orgId)
            ->where('user_id', $request->user()->id)
            ->exists();

        if (! $belongs) {
            return redirect()
                ->route('organizations.index')
                ->withErrors(['organization_id' => 'You are not a member of this organization.']);
        }

        $request->session()->put('active_organization_id', $orgId);

        return redirect()
            ->route('organizations.index')
            ->with('status', 'Active organization updated.');
    }
}
