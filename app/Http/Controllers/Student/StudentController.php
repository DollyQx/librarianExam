<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Doubt;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $activeMembership = $user ? $user->activeMembership() : null;
        $subjects = Subject::where('is_active', true)->take(4)->get();
        $availableTests = Quiz::where('is_active', true)->latest()->take(3)->get();
        $recentAttempts = QuizAttempt::with('quiz')
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get();
        $recentMaterials = StudyMaterial::where('is_active', true)->latest()->take(3)->get();
        $recentVideos = Video::where('is_active', true)->latest()->take(2)->get();
        $doubtsCount = Doubt::where('user_id', $user->id)->count();
        $repliedDoubtsCount = Doubt::where('user_id', $user->id)->where('status', 'replied')->count();

        return view('student.dashboard', compact(
            'user', 'activeMembership', 'subjects', 'availableTests', 'recentAttempts',
            'recentMaterials', 'recentVideos', 'doubtsCount', 'repliedDoubtsCount'
        ));
    }

    public function subjects()
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->with([
                'topics' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc');
                },
                'studyMaterials' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc')->take(5);
                },
                'videos' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc')->take(5);
                },
                'quizzes' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order', 'asc')->withCount('questions');
                }
            ])
            ->get();

        return view('student.subjects', compact('subjects'));
    }

    public function materials(Request $request)
    {
        $query = StudyMaterial::where('is_active', true)->with(['subject', 'topic']);
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        $materials = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12);
        $subjects = Subject::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('student.materials', compact('materials', 'subjects'));
    }

    public function viewMaterial(StudyMaterial $material)
    {
        if (!$material->is_active) {
            abort(404, 'Material not available.');
        }

        // Server-side Membership Access Control
        if ($material->isMembershipRequired()) {
            if (!Auth::check() || !Auth::user()->hasActiveMembership()) {
                return redirect()->route('membership.index')
                    ->with('error', 'Membership Required! Access to this PDF requires an active Studyly Membership.');
            }
        }

        return view('student.pdf_viewer', compact('material'));
    }

    public function streamMaterial(StudyMaterial $material)
    {
        if (!$material->is_active) {
            abort(404, 'Material not available.');
        }

        if ($material->isMembershipRequired()) {
            if (!Auth::check() || !Auth::user()->hasActiveMembership()) {
                abort(403, 'Membership Required');
            }
        }

        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'Requested study file is not available on server.');
        }

        $material->increment('downloads_count');
        $fullPath = Storage::disk('public')->path($material->file_path);
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . Str::slug($material->title) . '.pdf"',
        ]);
    }

    public function downloadMaterial(StudyMaterial $material)
    {
        if (!$material->is_active) {
            abort(404, 'Material not available.');
        }

        // Server-side Membership Access Control
        if ($material->isMembershipRequired()) {
            if (!Auth::check() || !Auth::user()->hasActiveMembership()) {
                return redirect()->route('membership.index')
                    ->with('error', 'Membership Required! Access to this PDF requires an active Studyly Membership.');
            }
        }

        if (!Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'Requested study file is not available on server.');
        }

        $material->increment('downloads_count');
        $fullPath = Storage::disk('public')->path($material->file_path);
        return response()->download($fullPath, Str::slug($material->title) . '.pdf');
    }

    public function videos(Request $request)
    {
        $query = Video::where('is_active', true)->with(['subject', 'topic']);
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        $videos = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12);
        $subjects = Subject::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('student.videos', compact('videos', 'subjects'));
    }

    public function tests(Request $request)
    {
        $query = Quiz::where('is_active', true)
            ->with(['subject', 'topic'])
            ->withCount('questions');

        if ($request->filled('type') && in_array($request->type, ['mock', 'subject', 'topic'])) {
            $query->where('type', $request->type);
        }

        $quizzes = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('student.tests', compact('quizzes'));
    }

    public function showTest(Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('tests')->with('error', 'This test series is currently unavailable.');
        }

        // Server-side Membership Access Control
        if ($quiz->isMembershipRequired()) {
            if (!Auth::check() || !Auth::user()->hasActiveMembership()) {
                return redirect()->route('membership.index')
                    ->with('error', 'Membership Required! Access to this test requires an active Studyly Membership.');
            }
        }

        // Security: Exclude 'is_correct' column from options payload to prevent client-side inspect element cheating
        $quiz->load(['questions' => function ($q) {
            $q->orderBy('order', 'asc')->with(['options' => function ($optQuery) {
                $optQuery->select('id', 'question_id', 'option_text', 'order')->orderBy('order', 'asc');
            }]);
        }]);

        if ($quiz->questions->isEmpty()) {
            return redirect()->route('tests')->with('error', 'No questions found in this test series yet.');
        }

        return view('student.test_engine', compact('quiz'));
    }

    public function submitTest(Request $request, Quiz $quiz)
    {
        if (!$quiz->is_active) {
            return redirect()->route('tests')->with('error', 'This test is no longer active.');
        }

        $request->validate([
            'time_taken_seconds' => 'required|integer|min:0',
            'answers' => 'nullable|array',
        ]);

        $quiz->load(['questions.options']);
        $userId = Auth::check() ? Auth::id() : null;
        $userAnswers = $request->input('answers', []);
        $timeTaken = (int) $request->input('time_taken_seconds', 0);

        $totalQuestions = $quiz->questions->count();
        $attemptedQuestions = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $unattemptedQuestions = 0;
        $rawScore = 0.0;

        // Create Attempt record (user_id is null for Guest attempts)
        $attempt = QuizAttempt::create([
            'user_id' => $userId,
            'quiz_id' => $quiz->id,
            'total_questions' => $totalQuestions,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        foreach ($quiz->questions as $question) {
            $selectedOptionId = $userAnswers[$question->id] ?? null;

            if ($selectedOptionId) {
                $selectedOption = $question->options->where('id', $selectedOptionId)->first();

                if ($selectedOption) {
                    $attemptedQuestions++;
                    $isCorrect = (bool) $selectedOption->is_correct;

                    if ($isCorrect) {
                        $correctAnswers++;
                        $marksAwarded = (float) $question->marks;
                        $rawScore += $marksAwarded;
                    } else {
                        $wrongAnswers++;
                        $marksAwarded = -1 * (float) $quiz->negative_marking_per_question;
                        $rawScore += $marksAwarded;
                    }

                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $selectedOption->id,
                        'is_correct' => $isCorrect,
                        'marks_awarded' => $marksAwarded,
                    ]);
                } else {
                    $unattemptedQuestions++;
                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => null,
                        'is_correct' => false,
                        'marks_awarded' => 0.00,
                    ]);
                }
            } else {
                $unattemptedQuestions++;
                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option_id' => null,
                    'is_correct' => false,
                    'marks_awarded' => 0.00,
                ]);
            }
        }

        $maxMarks = (float) ($totalQuestions * $quiz->marks_per_question);
        $finalScore = max(0.0, round($rawScore, 2));
        $percentage = ($maxMarks > 0) ? min(100.0, max(0.0, ($finalScore / $maxMarks) * 100)) : 0.0;

        $attempt->update([
            'total_questions' => $totalQuestions,
            'attempted_questions' => $attemptedQuestions,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'unattempted_questions' => $unattemptedQuestions,
            'marks_obtained' => $finalScore,
            'max_marks' => $maxMarks,
            'percentage' => round($percentage, 2),
            'status' => 'completed',
            'completed_at' => now(),
            'time_taken_seconds' => $timeTaken,
        ]);

        return redirect()->route('tests.result', [$quiz->id, $attempt->id])
            ->with('success', 'Test submitted successfully! Here is your scorecard.');
    }

    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        // Security check: If attempt belongs to a registered student, verify ownership.
        // If attempt belongs to a Guest (user_id === null), allow instant public viewing!
        if ($attempt->user_id !== null) {
            if (!Auth::check() || $attempt->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to test result.');
            }
        }

        if ($attempt->quiz_id !== $quiz->id) {
            abort(404, 'Test result not found for this quiz.');
        }

        $attempt->load(['quiz', 'answers.question.options', 'answers.selectedOption']);

        return view('student.result', compact('quiz', 'attempt'));
    }

    public function attempts()
    {
        $attempts = QuizAttempt::with('quiz')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('student.attempts', compact('attempts'));
    }

    public function doubts()
    {
        $doubts = Doubt::with(['subject', 'topic', 'replies.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $subjects = Subject::where('is_active', true)->get();

        return view('student.doubts', compact('doubts', 'subjects'));
    }

    public function storeDoubt(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'description' => 'required|string',
        ]);

        Doubt::create([
            'user_id' => Auth::id(),
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your doubt has been submitted! Teachers will respond shortly.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
