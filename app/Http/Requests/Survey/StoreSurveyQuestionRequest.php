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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'question_type' => 'required|string|in:single_choice,multiple_choice,text,scale_1_10',
            'data' => 'json|required_if:question_type,single_choice,multiple_choice',
            'survey_id' => 'required|exists:surveys,id',
        ];
    }
}
