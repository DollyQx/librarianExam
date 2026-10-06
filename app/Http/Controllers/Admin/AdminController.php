<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doubt;
use App\Models\DoubtReply;
use App\Models\Membership;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_subjects' => Subject::count(),
            'total_materials' => StudyMaterial::count(),
            'total_videos' => Video::count(),
            'total_quizzes' => Quiz::count(),
            'total_attempts' => QuizAttempt::count(),
            'pending_doubts' => Doubt::where('status', 'pending')->count(),
        ];

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])->latest()->take(5)->get();
        $pendingDoubtsList = Doubt::with(['user', 'subject'])->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentAttempts', 'pendingDoubtsList'));
    }

    // Students Management
    public function students(Request $request)
    {
        $query = User::where('role', 'student')->with(['memberships' => function($q) {
            $q->latest();
        }]);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $students = $query->latest()->paginate(15);
        return view('admin.students', compact('students'));
    }

    public function grantFreeMembership(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot grant membership to an admin account.');
        }

        $existingActive = $user->memberships()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();

        if ($existingActive) {
            $startsAt = $existingActive->starts_at;
            $expiresAt = $existingActive->expires_at->copy()->addDays(30);
        } else {
            $startsAt = now();
            $expiresAt = now()->addDays(30);
        }

        Membership::create([
            'user_id' => $user->id,
            'plan_name' => 'Studyly Membership',
            'price' => 0.00,
            'status' => 'active',
            'payment_method' => 'admin_granted',
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
        ]);

        return back()->with('success', 'Free membership granted to ' . $user->name . ' for 1 month.');
    }

    public function revokeMembership(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin account status.');
        }

        $user->memberships()
            ->where('status', 'active')
            ->update(['status' => 'revoked']);

        return back()->with('success', 'Active membership revoked for ' . $user->name . '. Financial history preserved.');
    }

    public function toggleStudentStatus(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin account status.');
        }
        $user->status = ($user->status === 'active') ? 'inactive' : 'active';
        $user->save();
        return back()->with('success', 'Student status updated successfully.');
    }

    // Subject CRUD
    public function subjects()
    {
        $subjects = Subject::withCount(['topics', 'studyMaterials', 'videos', 'quizzes'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_class' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Subject::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon_class' => $request->icon_class ?: 'fas fa-book',
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Subject created successfully.');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_class' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $subject->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon_class' => $request->icon_class ?: 'fas fa-book',
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Subject updated successfully.');
    }

    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Subject deleted successfully.');
    }

    // Topic CRUD
    public function topics()
    {
        $topics = Topic::with('subject')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        return view('admin.topics', compact('topics', 'subjects'));
    }

    public function storeTopic(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Topic::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Topic created successfully.');
    }

    public function updateTopic(Request $request, Topic $topic)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $topic->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Topic updated successfully.');
    }

    public function deleteTopic(Topic $topic)
    {
        $topic->delete();
        return back()->with('success', 'Topic deleted successfully.');
    }

    // PDF Study Materials Management
    public function materials()
    {
        $materials = StudyMaterial::with(['subject', 'topic'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);
        $subjects = Subject::where('is_active', true)->with('topics')->get();
        return view('admin.materials', compact('materials', 'subjects'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_type' => 'nullable|in:free,membership',
            'pdf_file' => 'required|file|mimes:pdf|max:20480',
            'sort_order' => 'nullable|integer',
        ]);

        $file = $request->file('pdf_file');
        $fileName = time() . '_' . Str::slug($request->title) . '.pdf';
        $filePath = $file->storeAs('study_materials', $fileName, 'public');

        $accessType = $request->input('access_type', 'free');
        $isPaid = ($accessType === 'membership');

        StudyMaterial::create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'access_type' => $accessType,
            'is_paid' => $isPaid,
            'price' => 0.00,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'PDF Study Material uploaded successfully.');
    }

    public function updateMaterial(Request $request, StudyMaterial $material)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_type' => 'nullable|in:free,membership',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'sort_order' => 'nullable|integer',
        ]);

        $accessType = $request->input('access_type', $material->access_type ?? 'free');
        $isPaid = ($accessType === 'membership');

        $data = [
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'description' => $request->description,
            'access_type' => $accessType,
            'is_paid' => $isPaid,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];

        if ($request->hasFile('pdf_file')) {
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $file = $request->file('pdf_file');
            $fileName = time() . '_' . Str::slug($request->title) . '.pdf';
            $data['file_path'] = $file->storeAs('study_materials', $fileName, 'public');
            $data['file_size'] = $file->getSize();
        }

        $material->update($data);

        return back()->with('success', 'Study Material updated successfully.');
    }

    public function deleteMaterial(StudyMaterial $material)
    {
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return back()->with('success', 'PDF Study Material deleted.');
    }

    // Video Management
    public function videos()
    {
        $videos = Video::with(['subject', 'topic'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);
        $subjects = Subject::where('is_active', true)->with('topics')->get();
        return view('admin.videos', compact('videos', 'subjects'));
    }

    public function storeVideo(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_type' => 'nullable|in:free,membership',
            'youtube_url' => 'required|url',
            'thumbnail_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $youtubeId = Video::parseYoutubeId($request->youtube_url);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Invalid YouTube URL. Please provide a valid YouTube video link.'])->withInput();
        }

        $thumbnail = $request->filled('thumbnail_url') 
            ? $request->thumbnail_url 
            : "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";

        Video::create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'description' => $request->description,
            'access_type' => $request->input('access_type', 'free'),
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnail,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'YouTube Video added successfully.');
    }

    public function updateVideo(Request $request, Video $video)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_type' => 'nullable|in:free,membership',
            'youtube_url' => 'required|url',
            'thumbnail_url' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $youtubeId = Video::parseYoutubeId($request->youtube_url);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Invalid YouTube URL. Please provide a valid YouTube video link.'])->withInput();
        }

        $thumbnail = $request->filled('thumbnail_url') 
            ? $request->thumbnail_url 
            : "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";

        $video->update([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'description' => $request->description,
            'access_type' => $request->input('access_type', $video->access_type ?? 'free'),
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnail,
            'is_active' => $request->has('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Video updated successfully.');
    }

    public function deleteVideo(Video $video)
    {
        $video->delete();
        return back()->with('success', 'Video deleted.');
    }

    // Quizzes / Mock Tests Management
    public function quizzes()
    {
        $quizzes = Quiz::with(['subject', 'topic'])
            ->withCount('questions')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        $subjects = Subject::where('is_active', true)->with('topics')->get();
        return view('admin.quizzes', compact('quizzes', 'subjects'));
    }

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:topic,subject,mock',
            'access_type' => 'nullable|in:free,membership',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'marks_per_question' => 'required|numeric|min:0.25',
            'negative_marking_per_question' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        $isActive = $request->has('is_active');
        if ($isActive) {
            return back()->withErrors(['is_active' => 'New test series must be saved as Draft first to add questions before publishing.'])->withInput();
        }

        $accessType = $request->input('access_type', 'free');
        $isPaid = ($accessType === 'membership');

        Quiz::create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'type' => $request->type,
            'access_type' => $accessType,
            'is_paid' => $isPaid,
            'price' => 0.00,
            'duration_minutes' => $request->duration_minutes,
            'pass_percentage' => $request->pass_percentage,
            'marks_per_question' => $request->marks_per_question,
            'negative_marking_per_question' => $request->negative_marking_per_question,
            'is_active' => false,
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Quiz created as Draft. Please add questions before activating.');
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:topic,subject,mock',
            'access_type' => 'nullable|in:free,membership',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'marks_per_question' => 'required|numeric|min:0.25',
            'negative_marking_per_question' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        $isActive = $request->has('is_active');

        // Publishing Validation: Check if quiz has valid questions and correct options before activating
        if ($isActive && !$quiz->hasValidQuestions()) {
            return back()->withErrors(['is_active' => 'Cannot publish test series! The test must have at least 1 question with a designated correct answer.'])->withInput();
        }

        $accessType = $request->input('access_type', $quiz->access_type ?? 'free');
        $isPaid = ($accessType === 'membership');

        $quiz->update([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $quiz->id,
            'description' => $request->description,
            'type' => $request->type,
            'access_type' => $accessType,
            'is_paid' => $isPaid,
            'duration_minutes' => $request->duration_minutes,
            'pass_percentage' => $request->pass_percentage,
            'marks_per_question' => $request->marks_per_question,
            'negative_marking_per_question' => $request->negative_marking_per_question,
            'is_active' => $isActive,
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Quiz updated successfully.');
    }

    public function deleteQuiz(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz deleted.');
    }

    // Questions Management for Quiz
    public function questions(Quiz $quiz)
    {
        $quiz->load(['questions' => function($q) {
            $q->orderBy('order', 'asc')->with(['options' => function($opt) {
                $opt->orderBy('order', 'asc');
            }]);
        }]);
        return view('admin.questions', compact('quiz'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
            'marks' => 'nullable|numeric|min:0',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
            'marks' => $request->filled('marks') ? (float)$request->marks : $quiz->marks_per_question,
            'order' => $quiz->questions()->count() + 1,
        ]);

        foreach ($request->options as $index => $optionText) {
            QuizOption::create([
                'question_id' => $question->id,
                'option_text' => $optionText,
                'is_correct' => ((int)$request->correct_option === $index),
                'order' => $index + 1,
            ]);
        }

        return back()->with('success', 'Question & options added successfully.');
    }

    public function updateQuestion(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
            'order' => 'nullable|integer|min:1',
            'marks' => 'nullable|numeric|min:0',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
            'marks' => $request->filled('marks') ? (float)$request->marks : $question->marks,
            'order' => $request->filled('order') ? (int)$request->order : $question->order,
        ]);

        // Delete existing options and recreate with updated selections
        $question->options()->delete();

        foreach ($request->options as $index => $optionText) {
            QuizOption::create([
                'question_id' => $question->id,
                'option_text' => $optionText,
                'is_correct' => ((int)$request->correct_option === $index),
                'order' => $index + 1,
            ]);
        }

        return back()->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion(Question $question)
    {
        $quiz = $question->quiz;
        $question->delete();

        // If deleting this question leaves quiz with 0 valid questions, automatically set quiz as inactive
        if ($quiz && !$quiz->hasValidQuestions()) {
            $quiz->update(['is_active' => false]);
        }

        return back()->with('success', 'Question deleted.');
    }

    // Attempts Monitor
    public function attempts()
    {
        $attempts = QuizAttempt::with(['user', 'quiz'])->latest()->paginate(20);
        return view('admin.attempts', compact('attempts'));
    }

    // Doubts Queue
    public function doubts()
    {
        $doubts = Doubt::with(['user', 'subject', 'topic', 'replies.user'])->latest()->paginate(15);
        return view('admin.doubts', compact('doubts'));
    }

    public function replyDoubt(Request $request, Doubt $doubt)
    {
        $request->validate([
            'reply_text' => 'required|string',
        ]);

        DoubtReply::create([
            'doubt_id' => $doubt->id,
            'user_id' => auth()->id(),
            'reply_text' => $request->reply_text,
        ]);

        $doubt->status = 'replied';
        $doubt->save();

        return back()->with('success', 'Reply submitted successfully.');
    }

    // Bulk Quiz Import Methods
    public function importQuizzesForm()
    {
        return view('admin.import_quizzes');
    }

    public function downloadImportTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="quiz_import_template.csv"',
        ];

        $columns = [
            'quiz_title',
            'subject',
            'topic',
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_answer',
            'explanation',
            'marks',
            'negative_marks',
            'duration_minutes',
            'access_type',
        ];

        $sampleData = [
            [
                'quiz_title' => 'Bihar Librarian Mock Test 1',
                'subject' => 'Library Science',
                'topic' => 'Classification',
                'question' => 'Who is considered the father of Library Science in India?',
                'option_a' => 'Dr. S.R. Ranganathan',
                'option_b' => 'Melvil Dewey',
                'option_c' => 'C.A. Cutter',
                'option_d' => 'W.C. Berwick Sayers',
                'correct_answer' => 'A',
                'explanation' => 'Dr. S.R. Ranganathan is known as the father of library science in India.',
                'marks' => '1.0',
                'negative_marks' => '0.25',
                'duration_minutes' => '60',
                'access_type' => 'membership',
            ],
            [
                'quiz_title' => 'Bihar Librarian Mock Test 1',
                'subject' => 'Library Science',
                'topic' => 'Classification',
                'question' => 'Colon Classification was published in which year?',
                'option_a' => '1933',
                'option_b' => '1928',
                'option_c' => '1944',
                'option_d' => '1950',
                'correct_answer' => 'A',
                'explanation' => 'Colon Classification (CC) by S.R. Ranganathan was first published in 1933.',
                'marks' => '1.0',
                'negative_marks' => '0.25',
                'duration_minutes' => '60',
                'access_type' => 'membership',
            ],
            [
                'quiz_title' => 'Bihar LET Free Practice Quiz',
                'subject' => 'General Knowledge',
                'topic' => 'General Awareness',
                'question' => 'What is the capital of Bihar?',
                'option_a' => 'Patna',
                'option_b' => 'Gaya',
                'option_c' => 'Muzaffarpur',
                'option_d' => 'Bhagalpur',
                'correct_answer' => 'A',
                'explanation' => 'Patna is the capital city of the Indian state of Bihar.',
                'marks' => '1.0',
                'negative_marks' => '0.00',
                'duration_minutes' => '15',
                'access_type' => 'free',
            ],
        ];

        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, $columns);

            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function previewQuizImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:5120',
        ]);

        $file = $request->file('csv_file');
        $content = file_get_contents($file->getRealPath());

        // Strip UTF-8 BOM if present
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        $rows = [];
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $data = str_getcsv($line);
            if (array_filter($data)) {
                $rows[] = $data;
            }
        }

        if (count($rows) < 2) {
            return back()->with('error', 'CSV file is empty or missing data rows.');
        }

        $headers = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x7F\xEF\xBB\xBF]/u', '', $h)));
        }, $rows[0]);

        $requiredColumns = ['quiz_title', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $headers)) {
                return back()->with('error', "Missing required column in CSV: '{$col}'");
            }
        }

        $headerMap = array_flip($headers);
        $validationErrors = [];
        $validRows = [];
        $quizTitles = [];

        for ($i = 1; $i < count($rows); $i++) {
            $rowNum = $i + 1;
            $data = $rows[$i];

            $getValue = function($col) use ($headerMap, $data) {
                $idx = $headerMap[$col] ?? null;
                return ($idx !== null && isset($data[$idx])) ? trim($data[$idx]) : '';
            };

            $quizTitle = $getValue('quiz_title');
            $subject = $getValue('subject');
            $topic = $getValue('topic');
            $question = $getValue('question');
            $optionA = $getValue('option_a');
            $optionB = $getValue('option_b');
            $optionC = $getValue('option_c');
            $optionD = $getValue('option_d');
            $correctAnswer = strtoupper($getValue('correct_answer'));
            $explanation = $getValue('explanation');
            $marks = $getValue('marks');
            $negativeMarks = $getValue('negative_marks');
            $durationMinutes = $getValue('duration_minutes');
            $accessType = strtolower($getValue('access_type'));

            $rowErrors = [];

            if (empty($quizTitle)) {
                $rowErrors[] = 'quiz_title is required';
            }
            if (empty($question)) {
                $rowErrors[] = 'question text is required';
            }
            if (empty($optionA) || empty($optionB) || empty($optionC) || empty($optionD)) {
                $rowErrors[] = 'option_a, option_b, option_c, and option_d are required';
            }
            if (!in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
                $rowErrors[] = 'correct_answer must be A, B, C, or D';
            }
            if ($marks !== '' && !is_numeric($marks)) {
                $rowErrors[] = 'marks must be numeric';
            }
            if ($negativeMarks !== '' && !is_numeric($negativeMarks)) {
                $rowErrors[] = 'negative_marks must be numeric';
            }
            if ($durationMinutes !== '' && (!is_numeric($durationMinutes) || $durationMinutes < 1)) {
                $rowErrors[] = 'duration_minutes must be numeric >= 1';
            }
            if ($accessType !== '' && !in_array($accessType, ['free', 'membership'])) {
                $rowErrors[] = 'access_type must be free or membership';
            }

            if (!empty($rowErrors)) {
                $validationErrors[] = [
                    'row' => $rowNum,
                    'quiz_title' => $quizTitle ?: 'N/A',
                    'error' => implode(' | ', $rowErrors),
                ];
            } else {
                $validRows[] = [
                    'row_num' => $rowNum,
                    'quiz_title' => $quizTitle,
                    'subject' => $subject,
                    'topic' => $topic,
                    'question' => $question,
                    'option_a' => $optionA,
                    'option_b' => $optionB,
                    'option_c' => $optionC,
                    'option_d' => $optionD,
                    'correct_answer' => $correctAnswer,
                    'explanation' => $explanation,
                    'marks' => $marks !== '' ? (float)$marks : 1.0,
                    'negative_marks' => $negativeMarks !== '' ? (float)$negativeMarks : 0.25,
                    'duration_minutes' => $durationMinutes !== '' ? (int)$durationMinutes : 30,
                    'access_type' => $accessType ?: 'free',
                ];

                if (!in_array($quizTitle, $quizTitles)) {
                    $quizTitles[] = $quizTitle;
                }
            }
        }

        $existingQuizzes = Quiz::whereIn('title', $quizTitles)->pluck('title')->toArray();

        session([
            'bulk_quiz_import_rows' => $validRows,
            'bulk_quiz_import_errors' => $validationErrors,
        ]);

        return view('admin.import_quizzes', [
            'previewMode' => true,
            'validRows' => $validRows,
            'validationErrors' => $validationErrors,
            'quizTitles' => $quizTitles,
            'existingQuizzes' => $existingQuizzes,
            'totalRows' => count($rows) - 1,
        ]);
    }

    public function executeQuizImport(Request $request)
    {
        $validRows = session('bulk_quiz_import_rows');
        if (empty($validRows) && $request->has('csv_payload')) {
            $validRows = json_decode($request->input('csv_payload'), true);
        }

        $validationErrors = session('bulk_quiz_import_errors', []);

        if (empty($validRows)) {
            return redirect()->route('admin.quizzes.import')->with('error', 'No valid import session found or file had errors.');
        }

        if (!empty($validationErrors)) {
            return redirect()->route('admin.quizzes.import')->with('error', 'Import aborted because the file contains validation errors. Please fix all errors before importing.');
        }

        $duplicateMode = $request->input('duplicate_mode', 'add_to_existing');

        $quizzesCreated = 0;
        $questionsImported = 0;
        $skippedRows = 0;

        DB::transaction(function() use ($validRows, $duplicateMode, &$quizzesCreated, &$questionsImported, &$skippedRows) {
            $grouped = [];
            foreach ($validRows as $row) {
                $grouped[$row['quiz_title']][] = $row;
            }

            foreach ($grouped as $quizTitle => $quizRows) {
                $firstRow = $quizRows[0];

                $subjectId = null;
                if (!empty($firstRow['subject'])) {
                    $subjectName = trim($firstRow['subject']);
                    $subject = Subject::firstOrCreate(
                        ['name' => $subjectName],
                        [
                            'slug' => Str::slug($subjectName),
                            'description' => $subjectName,
                            'is_active' => true,
                        ]
                    );
                    $subjectId = $subject->id;
                }

                $topicId = null;
                if (!empty($firstRow['topic']) && $subjectId) {
                    $topicName = trim($firstRow['topic']);
                    $topic = Topic::firstOrCreate(
                        [
                            'subject_id' => $subjectId,
                            'name' => $topicName,
                        ],
                        [
                            'slug' => Str::slug($topicName),
                            'is_active' => true,
                        ]
                    );
                    $topicId = $topic->id;
                }

                $existingQuiz = Quiz::where('title', $quizTitle)->first();

                if ($existingQuiz && $duplicateMode === 'create_new') {
                    $newTitle = $quizTitle . ' (Imported ' . date('d M Y H:i') . ')';
                    $quiz = Quiz::create([
                        'subject_id' => $subjectId,
                        'topic_id' => $topicId,
                        'title' => $newTitle,
                        'slug' => Str::slug($newTitle) . '-' . time() . '-' . rand(100, 999),
                        'description' => 'Bulk imported test series.',
                        'type' => $subjectId ? 'subject' : 'mock',
                        'duration_minutes' => $firstRow['duration_minutes'],
                        'pass_percentage' => 40,
                        'marks_per_question' => $firstRow['marks'],
                        'negative_marking_per_question' => $firstRow['negative_marks'],
                        'is_active' => true,
                        'access_type' => $firstRow['access_type'],
                        'is_paid' => ($firstRow['access_type'] === 'membership'),
                    ]);
                    $quizzesCreated++;
                } elseif ($existingQuiz && $duplicateMode === 'add_to_existing') {
                    $quiz = $existingQuiz;
                } else {
                    $quiz = Quiz::create([
                        'subject_id' => $subjectId,
                        'topic_id' => $topicId,
                        'title' => $quizTitle,
                        'slug' => Str::slug($quizTitle) . '-' . time() . '-' . rand(100, 999),
                        'description' => 'Bulk imported test series.',
                        'type' => $subjectId ? 'subject' : 'mock',
                        'duration_minutes' => $firstRow['duration_minutes'],
                        'pass_percentage' => 40,
                        'marks_per_question' => $firstRow['marks'],
                        'negative_marking_per_question' => $firstRow['negative_marks'],
                        'is_active' => true,
                        'access_type' => $firstRow['access_type'],
                        'is_paid' => ($firstRow['access_type'] === 'membership'),
                    ]);
                    $quizzesCreated++;
                }

                $existingOrder = $quiz->questions()->max('order') ?? 0;

                foreach ($quizRows as $qRow) {
                    $duplicateQuestion = Question::where('quiz_id', $quiz->id)
                        ->where('question_text', $qRow['question'])
                        ->first();

                    if ($duplicateQuestion) {
                        $skippedRows++;
                        continue;
                    }

                    $existingOrder++;
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $qRow['question'],
                        'explanation' => $qRow['explanation'] ?: null,
                        'marks' => $qRow['marks'],
                        'order' => $existingOrder,
                    ]);

                    $options = [
                        'A' => $qRow['option_a'],
                        'B' => $qRow['option_b'],
                        'C' => $qRow['option_c'],
                        'D' => $qRow['option_d'],
                    ];

                    $optOrder = 1;
                    foreach ($options as $key => $optText) {
                        QuizOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optText,
                            'is_correct' => ($key === $qRow['correct_answer']),
                            'order' => $optOrder++,
                        ]);
                    }

                    $questionsImported++;
                }
            }
        });

        session()->forget(['bulk_quiz_import_rows', 'bulk_quiz_import_errors']);

        return redirect()->route('admin.quizzes')->with('success', "Import Successful! Quizzes created/updated: {$quizzesCreated}, Questions imported: {$questionsImported}, Skipped duplicate rows: {$skippedRows}.");
    }

    // Bulk PDF / Study Material Import Methods
    public function importMaterialsForm()
    {
        $subjects = Subject::where('is_active', true)->with('topics')->get();
        return view('admin.import_materials', compact('subjects'));
    }

    public function previewMaterialImport(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'access_type' => 'required|in:free,membership',
            'description' => 'nullable|string',
            'pdf_files' => 'required|array|min:1',
            'pdf_files.*' => 'file',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $topic = $request->topic_id ? Topic::find($request->topic_id) : null;
        $accessType = $request->access_type;
        $description = $request->description;

        $filePayloads = [];
        $validationErrors = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($request->file('pdf_files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $rawName = pathinfo($originalName, PATHINFO_FILENAME);
            $title = trim(ucwords(str_replace(['_', '-'], ' ', $rawName)));

            $rowErrors = [];

            // File size limit: 50MB (52,428,800 bytes)
            if ($fileSize > 52428800) {
                $rowErrors[] = 'File exceeds maximum size (50MB)';
            }

            // Extension and MIME verification
            if ($extension !== 'pdf' || !in_array($mimeType, ['application/pdf', 'application/x-pdf', 'application/octet-stream'])) {
                $rowErrors[] = 'Invalid PDF file';
            }

            // PDF Magic Header Check (%PDF-)
            if (empty($rowErrors)) {
                $header = file_get_contents($file->getRealPath(), false, null, 0, 5);
                if (strpos($header, '%PDF') !== 0) {
                    $rowErrors[] = 'Invalid PDF file';
                }
            }

            // Duplicate title check
            if (empty($rowErrors)) {
                $existsInDb = StudyMaterial::where('title', $title)->exists();
                if ($existsInDb) {
                    $rowErrors[] = 'Duplicate material';
                }
            }

            if (!empty($rowErrors)) {
                $invalidCount++;
                $errorStr = implode(' | ', $rowErrors);
                $validationErrors[] = [
                    'file_name' => $originalName,
                    'error' => $errorStr,
                ];
                $filePayloads[] = [
                    'id' => Str::uuid()->toString(),
                    'original_name' => $originalName,
                    'size_bytes' => $fileSize,
                    'size_formatted' => $this->formatFileSize($fileSize),
                    'title' => $title,
                    'status' => 'invalid',
                    'error' => $errorStr,
                    'temp_path' => null,
                ];
            } else {
                $validCount++;
                $tempFilename = 'import_' . time() . '_' . Str::uuid() . '.pdf';
                $tempPath = $file->storeAs('tmp_pdf_imports', $tempFilename, 'local');

                $filePayloads[] = [
                    'id' => Str::uuid()->toString(),
                    'original_name' => $originalName,
                    'size_bytes' => $fileSize,
                    'size_formatted' => $this->formatFileSize($fileSize),
                    'title' => $title,
                    'status' => 'valid',
                    'error' => null,
                    'temp_path' => $tempPath,
                ];
            }
        }

        $subjects = Subject::where('is_active', true)->with('topics')->get();

        return view('admin.import_materials', [
            'previewMode' => true,
            'subjects' => $subjects,
            'selectedSubject' => $subject,
            'selectedTopic' => $topic,
            'accessType' => $accessType,
            'description' => $description,
            'filePayloads' => $filePayloads,
            'validationErrors' => $validationErrors,
            'totalFiles' => count($request->file('pdf_files')),
            'validCount' => $validCount,
            'invalidCount' => $invalidCount,
        ]);
    }

    public function executeMaterialImport(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'access_type' => 'required|in:free,membership',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.temp_path' => 'required|string',
            'items.*.size_bytes' => 'required|integer',
        ]);

        $subjectId = $request->subject_id;
        $topicId = $request->topic_id;
        $accessType = $request->access_type;
        $isPaid = ($accessType === 'membership');
        $description = $request->description;

        $createdMaterials = 0;
        $newlyStoredPublicPaths = [];
        $tempPathsToDelete = [];

        try {
            DB::transaction(function() use ($request, $subjectId, $topicId, $accessType, $isPaid, $description, &$createdMaterials, &$newlyStoredPublicPaths, &$tempPathsToDelete) {
                foreach ($request->items as $item) {
                    $tempPath = $item['temp_path'];

                    if (!Storage::disk('local')->exists($tempPath)) {
                        throw new \Exception("Temporary import file not found.");
                    }

                    $tempPathsToDelete[] = $tempPath;

                    $titleSlug = Str::slug($item['title']) ?: 'material';
                    $safeFilename = time() . '_' . $titleSlug . '_' . Str::random(6) . '.pdf';
                    $publicPath = 'study_materials/' . $safeFilename;

                    $stream = Storage::disk('local')->readStream($tempPath);
                    Storage::disk('public')->writeStream($publicPath, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    $newlyStoredPublicPaths[] = $publicPath;

                    StudyMaterial::create([
                        'subject_id' => $subjectId,
                        'topic_id' => $topicId,
                        'title' => $item['title'],
                        'description' => $description,
                        'file_path' => $publicPath,
                        'file_size' => (int) $item['size_bytes'],
                        'access_type' => $accessType,
                        'is_paid' => $isPaid,
                        'price' => 0.00,
                        'is_active' => true,
                        'sort_order' => 0,
                    ]);

                    $createdMaterials++;
                }
            });
        } catch (\Throwable $e) {
            foreach ($newlyStoredPublicPaths as $pubPath) {
                if (Storage::disk('public')->exists($pubPath)) {
                    Storage::disk('public')->delete($pubPath);
                }
            }

            foreach ($tempPathsToDelete as $tPath) {
                if (Storage::disk('local')->exists($tPath)) {
                    Storage::disk('local')->delete($tPath);
                }
            }

            return redirect()->route('admin.materials.import')->with('error', 'Import failed: ' . $e->getMessage() . ' Newly created files were cleaned up.');
        }

        foreach ($tempPathsToDelete as $tPath) {
            if (Storage::disk('local')->exists($tPath)) {
                Storage::disk('local')->delete($tPath);
            }
        }

        return redirect()->route('admin.materials')->with('success', "Bulk PDF Upload Successful! {$createdMaterials} PDF Study Materials imported successfully.");
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}


