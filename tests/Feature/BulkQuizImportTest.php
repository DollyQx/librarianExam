<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BulkQuizImportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
        ]);
    }

    public function test_admin_can_access_bulk_import_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.quizzes.import'));
        $response->assertStatus(200);
        $response->assertSee('Bulk Quiz Import');
    }

    public function test_student_cannot_access_bulk_import_page()
    {
        $response = $this->actingAs($this->student)->get(route('admin.quizzes.import'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_bulk_import_page()
    {
        $guestResponse = $this->get(route('admin.quizzes.import'));
        $guestResponse->assertRedirect(route('login'));
    }

    public function test_admin_can_download_import_template()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.quizzes.import.template'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_valid_csv_preview_and_import()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Imported Test 1\",\"Library Science\",\"Cataloguing\",\"What is AACR2?\",\"Cataloguing Code\",\"Classification Code\",\"Subject List\",\"Dictionary\",\"A\",\"Anglo-American Cataloguing Rules 2nd edition\",1.0,0.25,45,membership\n" .
            "\"Imported Test 1\",\"Library Science\",\"Cataloguing\",\"What is DDC?\",\"Dewey Decimal Classification\",\"Dictionary\",\"Document Code\",\"Data Center\",\"A\",\"Dewey Decimal Classification system\",1.0,0.25,45,membership";

        $file = UploadedFile::fake()->createWithContent('quizzes.csv', $csvContent);

        // Preview
        $previewResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), [
            'csv_file' => $file,
        ]);
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('Imported Test 1');

        $validRows = $previewResponse->viewData('validRows');

        // Execute Import via form payload
        $executeResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.execute'), [
            'duplicate_mode' => 'create_new',
            'csv_payload' => json_encode($validRows),
        ]);
        $executeResponse->assertRedirect(route('admin.quizzes'));
        $executeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('quizzes', [
            'title' => 'Imported Test 1',
            'access_type' => 'membership',
            'duration_minutes' => 45,
        ]);

        $quiz = Quiz::where('title', 'Imported Test 1')->first();
        $this->assertNotNull($quiz);
        $this->assertEquals(2, $quiz->questions()->count());
    }

    public function test_multiple_quizzes_imported_from_single_file()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Quiz Alpha\",\"Science\",\"Physics\",\"Question 1\",\"A1\",\"B1\",\"C1\",\"D1\",\"A\",\"Exp1\",1,0,30,free\n" .
            "\"Quiz Beta\",\"History\",\"Ancient\",\"Question 2\",\"A2\",\"B2\",\"C2\",\"D2\",\"B\",\"Exp2\",1,0,30,membership";

        $file = UploadedFile::fake()->createWithContent('quizzes.csv', $csvContent);

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $validRows = $previewResponse->viewData('validRows');

        $this->actingAs($this->admin)->post(route('admin.quizzes.import.execute'), [
            'duplicate_mode' => 'create_new',
            'csv_payload' => json_encode($validRows),
        ]);

        $this->assertDatabaseHas('quizzes', ['title' => 'Quiz Alpha', 'access_type' => 'free']);
        $this->assertDatabaseHas('quizzes', ['title' => 'Quiz Beta', 'access_type' => 'membership']);

        $quizAlpha = Quiz::where('title', 'Quiz Alpha')->first();
        $quizBeta = Quiz::where('title', 'Quiz Beta')->first();

        $this->assertEquals(1, $quizAlpha->questions()->count());
        $this->assertEquals(1, $quizBeta->questions()->count());
    }

    public function test_invalid_correct_answer_rejected()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Invalid Test\",\"Science\",\"Physics\",\"Question 1\",\"A1\",\"B1\",\"C1\",\"D1\",\"E\",\"Exp1\",1,0,30,free";

        $file = UploadedFile::fake()->createWithContent('invalid.csv', $csvContent);

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('correct_answer must be A, B, C, or D');

        // Attempt execute without valid rows payload should be redirected
        $executeResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.execute'), [
            'duplicate_mode' => 'create_new',
        ]);
        $executeResponse->assertRedirect(route('admin.quizzes.import'));
        $executeResponse->assertSessionHas('error');

        $this->assertDatabaseMissing('quizzes', ['title' => 'Invalid Test']);
    }

    public function test_missing_required_fields_rejected()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"\",\"Science\",\"Physics\",\"\",\"A1\",\"B1\",\"C1\",\"D1\",\"A\",\"Exp1\",1,0,30,free";

        $file = UploadedFile::fake()->createWithContent('missing.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $response->assertStatus(200);
        $response->assertSee('quiz_title is required');
        $response->assertSee('question text is required');
    }

    public function test_invalid_access_type_rejected()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Invalid Access Quiz\",\"Science\",\"Physics\",\"Question 1\",\"A1\",\"B1\",\"C1\",\"D1\",\"A\",\"Exp1\",1,0,30,paid";

        $file = UploadedFile::fake()->createWithContent('invalid_access.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $response->assertStatus(200);
        $response->assertSee('access_type must be free or membership');
    }

    public function test_adding_questions_to_existing_quiz()
    {
        $existingQuiz = Quiz::create([
            'title' => 'Existing Mock Series',
            'slug' => 'existing-mock-series',
            'description' => 'Existing description',
            'type' => 'mock',
            'duration_minutes' => 60,
            'pass_percentage' => 40,
            'marks_per_question' => 1.0,
            'negative_marking_per_question' => 0.25,
            'is_active' => true,
            'access_type' => 'membership',
            'is_paid' => true,
        ]);

        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Existing Mock Series\",\"Library Science\",\"Classification\",\"New Question 1\",\"Opt A\",\"Opt B\",\"Opt C\",\"Opt D\",\"B\",\"Solution\",1,0.25,60,membership";

        $file = UploadedFile::fake()->createWithContent('existing.csv', $csvContent);

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $validRows = $previewResponse->viewData('validRows');

        $this->actingAs($this->admin)->post(route('admin.quizzes.import.execute'), [
            'duplicate_mode' => 'add_to_existing',
            'csv_payload' => json_encode($validRows),
        ]);

        $this->assertEquals(1, Quiz::where('title', 'Existing Mock Series')->count());
        $this->assertEquals(1, $existingQuiz->fresh()->questions()->count());
    }

    public function test_membership_required_quiz_remains_protected_after_import()
    {
        $csvContent = "quiz_title,subject,topic,question,option_a,option_b,option_c,option_d,correct_answer,explanation,marks,negative_marks,duration_minutes,access_type\n" .
            "\"Protected Premium Quiz\",\"Library Science\",\"Cataloguing\",\"Question X\",\"Opt A\",\"Opt B\",\"Opt C\",\"Opt D\",\"A\",\"Solution\",1,0.25,60,membership";

        $file = UploadedFile::fake()->createWithContent('protected.csv', $csvContent);

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.quizzes.import.preview'), ['csv_file' => $file]);
        $validRows = $previewResponse->viewData('validRows');

        $this->actingAs($this->admin)->post(route('admin.quizzes.import.execute'), [
            'duplicate_mode' => 'create_new',
            'csv_payload' => json_encode($validRows),
        ]);

        $quiz = Quiz::where('title', 'Protected Premium Quiz')->first();
        $this->assertNotNull($quiz);
        $this->assertTrue($quiz->isMembershipRequired());

        // Non-member student access attempt should be redirected to /membership
        $response = $this->actingAs($this->student)->get(route('student.tests.show', $quiz->id));
        $response->assertRedirect(route('membership.index'));
    }
}
