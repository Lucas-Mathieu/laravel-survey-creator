<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SurveyQuestion;

class StoreSurveyQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check()  ;
    }

    /**
     * Prepare input before validation.
     */
    protected function prepareForValidation(): void
    {
        $options = $this->input('options');

        if ($options === null) {
            return;
        }

        // If already valid JSON, keep it
        json_decode($options);
        if (json_last_error() === JSON_ERROR_NONE) {
            return;
        }

        // Split on new lines or commas, trim and remove empties
        $items = preg_split('/\r\n|\r|\n|,/', $options);
        $items = array_filter(array_map('trim', $items), function ($v) {
            return $v !== '';
        });

        $this->merge([
            'options' => json_encode(array_values($items)),
        ]);
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
            'question_type' => 'required|string|in:multiple_choice,text,checkbox,unique_choice,scale_from_1_to_10',
            'options' => 'json|required_if:question_type,multiple_choice,unique_choice',
            'survey_id' => 'required|exists:surveys,id',
        ];
    }
}
