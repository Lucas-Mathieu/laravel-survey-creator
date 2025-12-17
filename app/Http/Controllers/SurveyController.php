<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Actions\Survey\DeleteSurveyAction;
use App\Actions\Survey\GenerateSurveyTokenAction;
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
use Illuminate\Support\Carbon;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $activeOrgId = $this->ensureActiveOrganization($request);

        // Only show surveys for the active organization.
        $surveys = $activeOrgId
            ? Survey::where('organization_id', $activeOrgId)->get()
            : collect();

        $this->authorize('viewAny', Survey::class);

        return view('survey', [
            'surveys' => $surveys,
            'activeOrganizationId' => $activeOrgId,
        ]);
    }

    public function create()
    {
        $activeOrgId = $this->ensureActiveOrganization(request());
        // Guard create access via policy.
        $this->authorize('create', Survey::class);

        return view('survey', [
            'activeOrganizationId' => $activeOrgId,
        ]);
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $storeSurvey)
    {
        $this->ensureActiveOrganization($request);
        $this->authorize('create', Survey::class);

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
        $this->authorize('update', $survey);

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
    public function storeQuestion(StoreSurveyQuestionRequest $request, Survey $survey, StoreSurveyQuestionAction $storeSurveyQuestion)
    {
        $dto = SurveyQuestionDTO::fromRequest($request);
        $question = $storeSurveyQuestion->handle($dto);

        return redirect()
            ->route('surveys.show', $survey)
            ->with('status', 'Question created successfully.');
    }

    public function show(Survey $survey)
    {
        $this->authorize('view', $survey);

        return response()->json([
            'data' => $survey,
        ]);
    }

    public function destroy(DeleteSurveyRequest $request, Survey $survey)
    {
        $this->authorize('delete', $survey);

        app(DeleteSurveyAction::class)->handle($survey);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey deleted successfully.');
    }

    public function generatePublicLink(Survey $survey, GenerateSurveyTokenAction $action)
    {
        if ((int) $survey->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $action->handle($survey);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Public link generated.');
    }

    public function publicShow(string $token)
    {
        $survey = Survey::where('public_token', $token)->firstOrFail();

        $now = Carbon::now();
        if ($now->lt(Carbon::parse($survey->start_date)) || $now->gt(Carbon::parse($survey->end_date))) {
            abort(403);
        }

        return view('survey_public', [
            'survey' => $survey,
        ]);
    }
}
