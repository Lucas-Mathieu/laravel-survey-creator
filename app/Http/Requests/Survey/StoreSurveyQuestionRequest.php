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
            'question_type' => 'required|string|in:multiple_choice,text,checkbox,unique_choice,scale_from_1_to_10',
            'options' => 'json|required_if:question_type,multiple_choice,unique_choice',
            'survey_id' => 'required|exists:surveys,id',
        ];
    }
}
