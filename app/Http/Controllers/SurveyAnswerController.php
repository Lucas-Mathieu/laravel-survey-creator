<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAnswerAction;
use App\DTOs\SurveyAnswerDTO;
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use App\Models\Survey;

class SurveyAnswerController extends Controller
{
    public function store(StoreSurveyAnswerRequest $request, Survey $survey, StoreSurveyAnswerAction $action)
    {
        $request->merge(['survey_id' => $survey->id]);

        $dto = SurveyAnswerDTO::fromRequest($request);
        $action->handle($dto);

        return redirect()
            ->route('surveys.show', $survey)
            ->with('status', 'Answers submitted successfully.');
    }
}
