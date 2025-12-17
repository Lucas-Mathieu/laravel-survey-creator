<?php
namespace App\Actions\Survey;

use App\Models\Survey;
use Illuminate\Support\Str;

final class GenerateSurveyTokenAction
{
    public function handle(Survey $survey): Survey
    {
        if ($survey->public_token) {
            return $survey;
        }

        do {
            $token = Str::random(32);
        } while (Survey::where('public_token', $token)->exists());

        $survey->public_token = $token;
        $survey->save();

        return $survey->refresh();
    }
}
