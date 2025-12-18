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
        if (! $survey->is_anonymous && ! $request->user()) {
            return redirect()
                ->route('login')
                ->with('status', 'Please sign in to answer this survey.');
        }

        $request->merge(['survey_id' => $survey->id]);

        if ($survey->is_anonymous) {
            $request->setUserResolver(fn () => null);
        }

        $dto = SurveyAnswerDTO::fromRequest($request);
        $action->handle($dto);

        return redirect()
            ->route('surveys.public', ['token' => $survey->public_token])
            ->with('status', 'Thanks for your response.');
    }
}
