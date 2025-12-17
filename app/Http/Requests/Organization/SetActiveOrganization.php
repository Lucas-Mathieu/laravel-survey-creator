<?php

namespace App\Http\Requests\Organization;

use App\Models\OrganizationUser;
use Illuminate\Foundation\Http\FormRequest;

class SetActiveOrganization extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $orgId = $this->input('organization_id');

        if (! $orgId) {
            return false;
        }

        return OrganizationUser::where('organization_id', $orgId)
            ->where('user_id', $this->user()?->id)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
        ];
    }
}
