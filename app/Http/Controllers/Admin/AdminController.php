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
}
