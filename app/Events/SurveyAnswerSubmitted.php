<?php

namespace App\Events;

use App\Models\Survey;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SurveyAnswerSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Survey $survey)
    {
    }
}
