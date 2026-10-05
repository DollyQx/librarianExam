<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->student = User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_subject_with_sort_order()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.subjects.store'), [
            'name' => 'Library Classification',
            'description' => 'Classification systems and notations',
            'icon_class' => 'fas fa-sitemap',
            'sort_order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('subjects', [
            'name' => 'Library Classification',
            'sort_order' => 10,
            'is_active' => true,
        ]);
    }

    public function test_admin_cannot_publish_new_quiz_without_questions()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.store'), [
            'title' => 'Empty Test Series',
            'type' => 'mock',
            'duration_minutes' => 30,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.25,
            'is_active' => '1',
        ]);

        $response->assertSessionHasErrors(['is_active']);
        $this->assertDatabaseMissing('quizzes', [
            'title' => 'Empty Test Series',
        ]);
    }

    public function test_admin_can_create_draft_quiz_add_question_and_publish()
    {
        // 1. Create Draft Quiz
        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.store'), [
            'title' => 'KVS Mock Test #1',
            'type' => 'mock',
            'duration_minutes' => 30,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.25,
        ]);

        $response->assertRedirect();
        $quiz = Quiz::where('title', 'KVS Mock Test #1')->first();
        $this->assertNotNull($quiz);
        $this->assertFalse($quiz->is_active);

        // 2. Add Question & Options
        $qResponse = $this->actingAs($this->admin)->post(route('admin.questions.store', $quiz->id), [
            'question_text' => 'What does DDC stand for?',
            'explanation' => 'Dewey Decimal Classification created by Melvil Dewey',
            'options' => ['Dewey Decimal Classification', 'Digital Data Code', 'Direct Document Control', 'Data Domain Center'],
            'correct_option' => 0,
        ]);

        $qResponse->assertRedirect();
        $this->assertTrue($quiz->fresh()->hasValidQuestions());

        // 3. Publish Quiz
        $publishResponse = $this->actingAs($this->admin)->put(route('admin.quizzes.update', $quiz->id), [
            'title' => $quiz->title,
            'type' => $quiz->type,
            'duration_minutes' => $quiz->duration_minutes,
            'pass_percentage' => $quiz->pass_percentage,
            'marks_per_question' => $quiz->marks_per_question,
            'negative_marking_per_question' => $quiz->negative_marking_per_question,
            'is_active' => '1',
        ]);

        $publishResponse->assertRedirect();
        $this->assertTrue($quiz->fresh()->is_active);
    }

    public function test_public_can_filter_tests_by_category()
    {
        $subject = Subject::create([
            'name' => 'Cataloging',
            'slug' => 'cataloging',
            'description' => 'Cataloging principles',
            'is_active' => true,
        ]);

        $quiz1 = Quiz::create([
            'title' => 'Full Mock #1',
            'slug' => 'full-mock-1',
            'type' => 'mock',
            'duration_minutes' => 30,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.0,
            'is_active' => true,
        ]);

        $quiz2 = Quiz::create([
            'subject_id' => $subject->id,
            'title' => 'Cataloging Practice Test',
            'slug' => 'cataloging-practice-test',
            'type' => 'subject',
            'duration_minutes' => 15,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.0,
            'is_active' => true,
        ]);

        // Filter by mock
        $responseMock = $this->get('/tests?type=mock');
        $responseMock->assertStatus(200);
        $responseMock->assertSee('Full Mock #1');
        $responseMock->assertDontSee('Cataloging Practice Test');

        // Filter by subject
        $responseSubject = $this->get('/tests?type=subject');
        $responseSubject->assertStatus(200);
        $responseSubject->assertSee('Cataloging Practice Test');
        $responseSubject->assertDontSee('Full Mock #1');
    }

    public function test_robots_txt_and_sitemap_accessibility()
    {
        $robotsResponse = $this->get('/robots.txt');
        $robotsResponse->assertStatus(200);
        $robotsResponse->assertSee('Disallow: /admin/');

        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertStatus(200);
        $sitemapResponse->assertHeader('Content-Type', 'application/xml');
    }
}
