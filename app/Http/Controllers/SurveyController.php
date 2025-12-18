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
        // Ensure an active organization exists for scoping surveys.
        $activeOrgId = $this->ensureActiveOrganization($request);

        // Only show surveys for the active organization.
        $surveys = $activeOrgId
            ? Survey::where('organization_id', $activeOrgId)
                ->where('survey_closed', false)
                ->get()
            : collect();

        $this->authorize('viewAny', Survey::class);

        return view('survey', [
            'surveys' => $surveys,
            'activeOrganizationId' => $activeOrgId,
        ]);
    }

    public function create()
    {
        // Initialize active org context before creating a survey.
        $activeOrgId = $this->ensureActiveOrganization(request());
        // Guard create access via policy.
        $this->authorize('create', Survey::class);

        return view('survey', [
            'activeOrganizationId' => $activeOrgId,
        ]);
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $storeSurvey)
    {
        // Ensure active org context and authorize creation.
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
        // Load surveys for the user's organizations (used for list + edit view).
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
        // Only owner/admin can update.
        $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);
        $updateSurvey->handle($survey, $dto);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey updated successfully.');
    }

    public function createQuestion(Request $request, Survey $survey)
    {
        // Render the question creation form for this survey.
        return view('survey_question_create', [
            'survey' => $survey,
        ]);
    }
    public function storeQuestion(StoreSurveyQuestionRequest $request, Survey $survey, StoreSurveyQuestionAction $storeSurveyQuestion)
    {
        // Persist a question linked to this survey.
        $dto = SurveyQuestionDTO::fromRequest($request);
        $storeSurveyQuestion->handle($dto);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Question created successfully.');
    }

    public function show(Survey $survey)
    {
        // Show survey JSON for internal usage.
        $this->authorize('view', $survey);

        return response()->json([
            'data' => $survey,
        ]);
    }

    public function destroy(DeleteSurveyRequest $request, Survey $survey)
    {
        // Only owner/admin can delete.
        $this->authorize('delete', $survey);

        app(DeleteSurveyAction::class)->handle($survey);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey deleted successfully.');
    }

    public function generatePublicLink(Survey $survey, GenerateSurveyTokenAction $action)
    {
        // Only the survey owner can generate a public token.
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
        // Public survey entrypoint by token.
        $survey = Survey::where('public_token', $token)->firstOrFail();

        // Block access if survey is closed.
        if ($survey->survey_closed) {
            abort(403);
        }

        // For non-anonymous surveys, require login.
        if (! $survey->is_anonymous && ! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('status', 'Please sign in to answer this survey.');
        }

        return view('survey_public', [
            'survey' => $survey,
            'questions' => $survey->questions()->orderBy('id')->get(),
        ]);
    }
}
