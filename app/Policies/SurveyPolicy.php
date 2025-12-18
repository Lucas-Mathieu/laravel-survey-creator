<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;
use App\Models\OrganizationUser;

class SurveyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return OrganizationUser::where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survey $survey): bool
    {
        if (OrganizationUser::where('organization_id', $survey->organization_id)->where('user_id', $user->id)->exists()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $orgId = session('active_organization_id') ?? session('organization_id');

        if (! $orgId) {
            return false;
        }

        return OrganizationUser::where('organization_id', $orgId)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survey $survey): bool
    {
        $isOwner = $survey->user_id === $user->id;

        if (OrganizationUser::where('organization_id', $survey->organization_id)->where('user_id', $user->id)->where('role', 'admin')->exists()) {
            return true;
        } elseif ($isOwner) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool
    {
        $isOwner = $survey->user_id === $user->id;

        if (OrganizationUser::where('organization_id', $survey->organization_id)->where('user_id', $user->id)->where('role', 'admin')->exists()) {
            return true;
        } elseif ($isOwner) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Survey $survey): bool
    {
        return $this->delete($user, $survey);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Survey $survey): bool
    {
        return $this->delete($user, $survey);
    }

    /**
     * Determine whether the user can view results of the survey.
     */
    public function viewResults(User $user, Survey $survey): bool
    {
        return $this->view($user, $survey);
    }
}
