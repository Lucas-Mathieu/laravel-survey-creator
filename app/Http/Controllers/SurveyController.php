<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Requests\Survey\DeleteSurveyRequest;
use App\DTOs\SurveyDTO;
use App\DTOs\SurveyQuestionDTO;
use App\Models\Survey;
use App\Models\OrganizationUser;
use App\Models\SurveyQuestion;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $orgIds = OrganizationUser::where('user_id', $request->user()->id)
            ->pluck('organization_id');

        $surveys = Survey::whereIn('organization_id', $orgIds)->get();

        return view('survey', [
            'surveys' => $surveys,
        ]);
    }

    public function create()
    {
        return view('survey');
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $storeSurvey)
    {
        $dto = SurveyDTO::fromRequest($request);
        $survey = $storeSurvey->handle($dto);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey created successfully.');
    }

    public function edit(Request $request, Survey $survey)
    {
        $orgIds = OrganizationUser::where('user_id', $request->user()->id)
            ->pluck('organization_id');

        $surveys = Survey::whereIn('organization_id', $orgIds)->get();

        return view('survey', [
            'survey' => $survey,
            'surveys' => $surveys,
        ]);
    }

    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $updateSurvey)
    {
        $dto = SurveyDTO::fromRequest($request);
        $updateSurvey->handle($survey, $dto);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey updated successfully.');
    }

    public function createQuestion(Request $request, Survey $survey)
    {
        return view('survey_question_create', [
            'survey' => $survey,
        ]);
    }
    public function storeQuestion(StoreSurveyQuestionRequest $request, Survey $survey)
    {
        $dto = SurveyQuestionDTO::fromRequest($request);
        $question = $storeSurveyQuestion->handle($dto);

        return redirect()
            ->route('surveys.show', $survey)
            ->with('status', 'Question created successfully.');
    }

    public function show(Survey $survey)
    {
        return response()->json([
            'data' => $survey,
        ]);
    }

    public function destroy(DeleteSurveyRequest $request, Survey $survey)
    {
        $survey->delete();

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey deleted successfully.');
    }
}
