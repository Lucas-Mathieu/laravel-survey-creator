<?php

namespace Tests\Unit;

use App\Actions\Survey\StoreSurveyAnswerAction;
use App\DTOs\SurveyAnswerDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Listeners\SendNewAnswerNotification;
use App\Mail\NewSurveyAnswerMail;
use App\Models\Organization;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StoreSurveyAnswerActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_answers_correctly(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $org = Organization::create(['name' => 'Org', 'user_id' => $user->id]);
        $survey = Survey::create([
            'organization_id' => $org->id,
            'user_id' => $user->id,
            'title' => 'Test survey',
            'description' => 'desc',
            'start_date' => now(),
            'end_date' => now()->addDay(),
            'is_anonymous' => false,
            'survey_closed' => false,
            'notify_on_answer' => true,
        ]);
        $question = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'title' => 'Q1',
            'question_type' => 'single_choice',
            'data' => ['Yes', 'No'],
        ]);

        $dto = new SurveyAnswerDTO(
            surveyId: $survey->id,
            userId: $user->id,
            answers: [
                ['question_id' => $question->id, 'answer' => 'Yes'],
            ],
        );

        $action = app(StoreSurveyAnswerAction::class);
        $created = $action->handle($dto);

        $this->assertIsArray($created);
        $this->assertCount(1, $created);
        $this->assertDatabaseHas('survey_answers', [
            'survey_id' => $survey->id,
            'survey_question_id' => $question->id,
            'user_id' => $user->id,
            'answer' => 'Yes',
        ]);
    }

    public function test_listener_sends_mail_when_notify_enabled(): void
    {
        Mail::fake();

        $user = User::create([
            'name' => 'Owner',
            'first_name' => 'Owner',
            'last_name' => 'User',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);
        $org = Organization::create(['name' => 'Org', 'user_id' => $user->id]);
        $survey = Survey::create([
            'organization_id' => $org->id,
            'user_id' => $user->id,
            'title' => 'Test survey',
            'description' => 'desc',
            'start_date' => now(),
            'end_date' => now()->addDay(),
            'is_anonymous' => false,
            'survey_closed' => false,
            'notify_on_answer' => true,
        ]);

        $listener = new SendNewAnswerNotification();
        $listener->handle(new SurveyAnswerSubmitted($survey));

        Mail::assertQueued(NewSurveyAnswerMail::class, function ($mail) use ($survey) {
            return $mail->hasTo($survey->user->email);
        });
    }
}
