<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Actions\Survey\DeleteSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Requests\Survey\DeleteSurveyRequest;
use App\DTOs\SurveyDTO;
use App\Models\Survey;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function edit(Survey $survey)
    {
        $this->authorize('update', $survey);

        return view('survey', compact('survey'));
    }

    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $updateSurvey)
    {
        $this->authorize('update', $survey);

        $dto = SurveyDTO::fromRequest($request);
        $survey = $updateSurvey->handle($survey, $dto);

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey updated successfully.');
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
}
