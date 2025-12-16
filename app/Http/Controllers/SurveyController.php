<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\DTOs\SurveyDTO;
use App\Models\Survey;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $orgIds = OrganizationUser::where('user_id', $request->user()->id)
            ->pluck('organization_id');

        $surveys = Survey::whereIn('organization_id', $orgIds)->get();

        return view('surveys.index', [
            'surveys' => $surveys,
        ]);
    }

    public function create()
    {
        return view('survey');
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $storeSurvey): JsonResponse
    {
        $dto = SurveyDTO::fromRequest($request);
        $survey = $storeSurvey->handle($dto);

        return response()->json([
            'data' => $survey,
            'message' => 'Survey created successfully.',
        ], 201);
    }

    public function edit(Survey $survey)
    {
        return view('survey', compact('survey'));
    }

    public function update(UpdateSurveyRequest $request, Survey $survey, UpdateSurveyAction $updateSurvey)
    {
        $dto = SurveyDTO::fromRequest($request);
        $survey = $updateSurvey->handle($survey, $dto);

        return response()->json([
            'data' => $survey,
            'message' => 'Survey updated successfully.',
        ]);
    }

    public function show(Survey $survey)
    {
        return response()->json([
            'data' => $survey,
        ]);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return response()->json([
            'message' => 'Survey deleted successfully.',
        ]);
    }
}
