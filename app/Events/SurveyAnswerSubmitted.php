<?php

namespace App\Events;

use App\Models\Survey;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SurveyAnswerSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Survey $survey)
    {
    }

    /**
     * Create a new event instance.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('survey-answer-submitted'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'SurveyAnswerSubmitted';
    }
}
