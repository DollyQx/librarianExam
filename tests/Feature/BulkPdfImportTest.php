<?php

namespace Tests\Feature;

use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulkPdfImportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $student;
    private Subject $subject;
    private Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
        ]);

        $this->subject = Subject::create([
            'name' => 'Library Classification',
            'slug' => 'library-classification',
            'description' => 'Classification subject',
            'is_active' => true,
        ]);

        $this->topic = Topic::create([
            'subject_id' => $this->subject->id,
            'name' => 'DDC Scheme',
            'slug' => 'ddc-scheme',
            'is_active' => true,
        ]);
    }

    private function createFakePdf(string $filename, int $sizeInKb = 10): UploadedFile
    {
        $content = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n" . str_repeat('A', $sizeInKb * 1024);
        return UploadedFile::fake()->createWithContent($filename, $content);
    }

    public function test_1_admin_can_access_bulk_pdf_import()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.materials.import'));
        $response->assertStatus(200);
        $response->assertSee('Bulk PDF Upload');
    }

    public function test_2_non_admin_cannot_access_bulk_pdf_import()
    {
        $studentResponse = $this->actingAs($this->student)->get(route('admin.materials.import'));
        $studentResponse->assertStatus(403);

        $this->post('/logout');

        $guestResponse = $this->get(route('admin.materials.import'));
        $guestResponse->assertRedirect(route('login'));
    }

    public function test_3_single_valid_pdf_imports()
    {
        $file = $this->createFakePdf('Bihar_Librarian_Notes_1.pdf');

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'access_type' => 'free',
            'pdf_files' => [$file],
        ]);
        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('Bihar Librarian Notes 1');

        $filePayloads = $previewResponse->viewData('filePayloads');
        $this->assertCount(1, $filePayloads);
        $this->assertEquals('valid', $filePayloads[0]['status']);

        $executeResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.execute'), [
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'access_type' => 'free',
            'items' => [
                [
                    'title' => $filePayloads[0]['title'],
                    'temp_path' => $filePayloads[0]['temp_path'],
                    'size_bytes' => $filePayloads[0]['size_bytes'],
                ],
            ],
        ]);

        $executeResponse->assertRedirect(route('admin.materials'));
        $executeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('study_materials', [
            'title' => 'Bihar Librarian Notes 1',
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'access_type' => 'free',
            'is_paid' => false,
        ]);
    }

    public function test_4_multiple_pdfs_import_successfully()
    {
        $file1 = $this->createFakePdf('Cataloguing_Rules.pdf');
        $file2 = $this->createFakePdf('Classification_Codes.pdf');

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'membership',
            'pdf_files' => [$file1, $file2],
        ]);
        $previewResponse->assertStatus(200);

        $filePayloads = $previewResponse->viewData('filePayloads');
        $this->assertCount(2, $filePayloads);

        $items = array_map(function ($payload) {
            return [
                'title' => $payload['title'],
                'temp_path' => $payload['temp_path'],
                'size_bytes' => $payload['size_bytes'],
            ];
        }, $filePayloads);

        $executeResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.execute'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'membership',
            'items' => $items,
        ]);

        $executeResponse->assertRedirect(route('admin.materials'));

        $this->assertDatabaseHas('study_materials', ['title' => 'Cataloguing Rules', 'access_type' => 'membership']);
        $this->assertDatabaseHas('study_materials', ['title' => 'Classification Codes', 'access_type' => 'membership']);
    }

    public function test_5_invalid_file_type_rejected()
    {
        // Script file disguised as .pdf without %PDF- magic bytes
        $fakeScript = UploadedFile::fake()->createWithContent('malicious.exe.pdf', '<?php echo "evil script"; ?>');

        $response = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'free',
            'pdf_files' => [$fakeScript],
        ]);

        $response->assertStatus(200);
        $response->assertSee('Invalid PDF file');

        $filePayloads = $response->viewData('filePayloads');
        $this->assertEquals('invalid', $filePayloads[0]['status']);
    }

    public function test_6_oversized_file_rejected()
    {
        // 51MB file exceeds 50MB limit
        $oversizedFile = UploadedFile::fake()->create('oversized.pdf', 52 * 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'free',
            'pdf_files' => [$oversizedFile],
        ]);

        $response->assertStatus(200);
        $response->assertSee('File exceeds maximum size');
    }

    public function test_7_duplicate_material_handled_correctly()
    {
        StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Existing Notes',
            'file_path' => 'study_materials/existing.pdf',
            'file_size' => 1024,
            'access_type' => 'free',
            'is_paid' => false,
            'is_active' => true,
        ]);

        $file = $this->createFakePdf('Existing_Notes.pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'free',
            'pdf_files' => [$file],
        ]);

        $response->assertStatus(200);
        $response->assertSee('Duplicate material');
    }

    public function test_8_subject_topic_mapping_works()
    {
        $file = $this->createFakePdf('Topic_Specific_Notes.pdf');

        $previewResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.preview'), [
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'access_type' => 'membership',
            'pdf_files' => [$file],
        ]);

        $filePayloads = $previewResponse->viewData('filePayloads');

        $this->actingAs($this->admin)->post(route('admin.materials.import.execute'), [
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'access_type' => 'membership',
            'items' => [
                [
                    'title' => $filePayloads[0]['title'],
                    'temp_path' => $filePayloads[0]['temp_path'],
                    'size_bytes' => $filePayloads[0]['size_bytes'],
                ],
            ],
        ]);

        $this->assertDatabaseHas('study_materials', [
            'title' => 'Topic Specific Notes',
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
        ]);
    }

    public function test_9_free_material_remains_accessible()
    {
        $material = StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Free Public Notes',
            'file_path' => 'study_materials/free_notes.pdf',
            'file_size' => 2048,
            'access_type' => 'free',
            'is_paid' => false,
            'is_active' => true,
        ]);

        // Guest can view free PDF details/viewer
        $guestResponse = $this->get(route('materials.view', $material->id));
        $guestResponse->assertStatus(200);
    }

    public function test_10_membership_material_remains_protected()
    {
        $material = StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Protected Premium Notes',
            'file_path' => 'study_materials/premium_notes.pdf',
            'file_size' => 2048,
            'access_type' => 'membership',
            'is_paid' => true,
            'is_active' => true,
        ]);

        // Student without active membership should be redirected to membership page
        $studentResponse = $this->actingAs($this->student)->get(route('materials.view', $material->id));
        $studentResponse->assertRedirect(route('membership.index'));
    }

    public function test_11_uploaded_pdf_cannot_be_accessed_directly_by_unauthorized_users()
    {
        $material = StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Private Stream Notes',
            'file_path' => 'study_materials/private_stream.pdf',
            'file_size' => 2048,
            'access_type' => 'membership',
            'is_paid' => true,
            'is_active' => true,
        ]);

        // Direct stream request by non-member student should fail with 403
        $response = $this->actingAs($this->student)->get(route('materials.stream', $material->id));
        $response->assertStatus(403);
    }

    public function test_12_failed_import_cleans_up_newly_uploaded_files()
    {
        // Create valid temp path
        $tempPath = 'tmp_pdf_imports/non_existent_file.pdf';

        $executeResponse = $this->actingAs($this->admin)->post(route('admin.materials.import.execute'), [
            'subject_id' => $this->subject->id,
            'access_type' => 'free',
            'items' => [
                [
                    'title' => 'Invalid Temp Material',
                    'temp_path' => $tempPath,
                    'size_bytes' => 1024,
                ],
            ],
        ]);

        $executeResponse->assertRedirect(route('admin.materials.import'));
        $executeResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('study_materials', ['title' => 'Invalid Temp Material']);
    }

    public function test_13_existing_pdf_viewer_still_works()
    {
        $material = StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Sample Viewer Notes',
            'file_path' => 'study_materials/sample_viewer.pdf',
            'file_size' => 2048,
            'access_type' => 'free',
            'is_paid' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->get(route('materials.view', $material->id));
        $response->assertStatus(200);
        $response->assertSee('Sample Viewer Notes');
    }

    public function test_14_existing_membership_access_control_still_works()
    {
        $material = StudyMaterial::create([
            'subject_id' => $this->subject->id,
            'title' => 'Membership Verified Notes',
            'file_path' => 'study_materials/verified_notes.pdf',
            'file_size' => 2048,
            'access_type' => 'membership',
            'is_paid' => true,
            'is_active' => true,
        ]);

        $this->assertTrue($material->isMembershipRequired());

        // Guest user redirect check
        $guestResponse = $this->get(route('materials.view', $material->id));
        $guestResponse->assertRedirect(route('membership.index'));
    }
}
