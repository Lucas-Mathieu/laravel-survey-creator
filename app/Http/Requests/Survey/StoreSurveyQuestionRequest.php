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
        $questionType = $this->input('question_type');

        // If the question type doesn't need choices, ensure options is null so the 'json' rule won't run
        if ($questionType !== null && ! in_array($questionType, ['multiple_choice', 'unique_choice', 'checkbox'], true)) {
            $this->merge(['options' => null]);
            return;
        }

        $options = $this->input('options');

        if ($options === null) {
            return;
        }

        // Normalize empty string to null so validation rules like 'json' don't run when field is empty
        if (trim($options) === '') {
            $this->merge(['options' => null]);
            return;
        }

        // Normalize escaped newline sequences (e.g. "\r\n" or "\n") to real newlines
        $options = str_replace(['\\r\\n', '\\n', '\\r'], PHP_EOL, $options);

        // If client sent literal 'rn' (e.g. from certain editors), convert to newline when no real newlines exist
        if (strpos($options, "\n") === false && strpos($options, "\r") === false && strpos($options, 'rn') !== false) {
            $options = str_replace('rn', PHP_EOL, $options);
        }

        // If already valid JSON, keep it
        json_decode($options);
        if (json_last_error() === JSON_ERROR_NONE) {
            $this->merge(['options' => $options]);
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
            // Options are optional unless the question type requires choices
            'options' => 'nullable|json|required_if:question_type,multiple_choice,unique_choice',
            'survey_id' => 'required|exists:surveys,id',
        ];
    }
}
