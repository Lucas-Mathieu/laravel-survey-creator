<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $survey = $this->route('survey');

        return $this->user()?->can('update', $survey) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_anonymous' => 'required|boolean',
            'notify_on_answer' => 'sometimes|boolean',
        ];
    }

    /**
     * Normalize incoming data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_anonymous' => $this->boolean('is_anonymous'),
        ]);
    }
}
