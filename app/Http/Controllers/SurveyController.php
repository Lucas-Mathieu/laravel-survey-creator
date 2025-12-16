<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
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
    private function ensureActiveOrganization(Request $request): ?int
    {
        $orgIds = OrganizationUser::where('user_id', $request->user()->id)
            ->pluck('organization_id');

        $activeOrgId = $request->session()->get('active_organization_id');

        if (!$activeOrgId && $orgIds->isNotEmpty()) {
            $activeOrgId = $orgIds->first();
            $request->session()->put('active_organization_id', $activeOrgId);
        }

        return $activeOrgId;
    }

    public function index(Request $request)
    {
        $activeOrgId = $this->ensureActiveOrganization($request);

        $surveys = $activeOrgId
            ? Survey::where('organization_id', $activeOrgId)->get()
            : collect();

        return view('survey', [
            'surveys' => $surveys,
            'activeOrganizationId' => $activeOrgId,
        ]);
    }

    public function create()
    {
        $this->ensureActiveOrganization(request());

        return view('survey');
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

        $survey->delete();

        return redirect()
            ->route('surveys.index')
            ->with('status', 'Survey deleted successfully.');
    }
}
