<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFirstAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_accessible_without_authentication(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/subjects')->assertStatus(200);
        $this->get('/materials')->assertStatus(200);
        $this->get('/videos')->assertStatus(200);
        $this->get('/tests')->assertStatus(200);
    }

    public function test_guest_can_take_quiz_and_view_result(): void
    {
        $subject = Subject::create([
            'name' => 'Library Classification',
            'slug' => 'library-classification',
            'is_active' => true,
        ]);

        $quiz = Quiz::create([
            'subject_id' => $subject->id,
            'title' => 'DDC & UDC Basics',
            'slug' => 'ddc-udc-basics',
            'type' => 'mock_test',
            'duration_minutes' => 10,
            'pass_percentage' => 50.00,
            'marks_per_question' => 1.00,
            'negative_marking_per_question' => 0.25,
            'is_active' => true,
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Who created the Dewey Decimal Classification?',
            'marks' => 1.00,
            'order' => 1,
        ]);

        $correctOption = QuizOption::create([
            'question_id' => $question->id,
            'option_text' => 'Melvil Dewey',
            'is_correct' => true,
            'order' => 1,
        ]);

        $wrongOption = QuizOption::create([
            'question_id' => $question->id,
            'option_text' => 'S.R. Ranganathan',
            'is_correct' => false,
            'order' => 2,
        ]);

        // 1. Guest can access test engine page
        $response = $this->get(route('tests.show', $quiz->id));
        $response->assertStatus(200);
        $response->assertSee('DDC & UDC Basics');
        $response->assertSee('Melvil Dewey');

        // 2. Guest submits test with correct answer
        $submitResponse = $this->post(route('tests.submit', $quiz->id), [
            'time_taken_seconds' => 45,
            'answers' => [
                $question->id => $correctOption->id,
            ],
        ]);

        $attempt = QuizAttempt::latest()->first();
        $this->assertNotNull($attempt);
        $this->assertNull($attempt->user_id);
        $this->assertEquals(1.00, $attempt->marks_obtained);
        $this->assertEquals(100.00, $attempt->percentage);

        $submitResponse->assertRedirect(route('tests.result', [$quiz->id, $attempt->id]));

        // 3. Guest can view scorecard & explanation
        $resultResponse = $this->get(route('tests.result', [$quiz->id, $attempt->id]));
        $resultResponse->assertStatus(200);
        $resultResponse->assertSee('Test Passed');
        $resultResponse->assertSee('Want to Save Your Test Result & History?', false);
    }

    public function test_authenticated_student_can_access_personal_dashboard(): void
    {
        $student = User::create([
            'name' => 'John Student',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('John Student');
    }

    public function test_admin_routes_are_protected_from_unauthenticated_and_students(): void
    {
        // Guests redirected to login
        $this->get('/admin')->assertRedirect('/login');

        // Students receive 403 Forbidden
        $student = User::create([
            'name' => 'Jane Student',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $this->actingAs($student)->get('/admin')->assertStatus(403);
    }
}
