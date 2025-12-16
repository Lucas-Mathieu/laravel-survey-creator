<?php

namespace App\Http\Controllers;

use App\Actions\Survey\CreateSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\DTOs\SurveyDTO;

class SurveyController extends Controller
{
    public function store(StoreSurveyRequest $request, CreateSurveyAction $createSurvey): JsonResponse
    {
        $dto = SurveyDTO::fromRequest($request);
        $survey = $createSurvey->handle($dto);

        return response()->json([
            'data' => $survey,
            'message' => 'Survey created successfully.',
        ], 201);
    }
}
