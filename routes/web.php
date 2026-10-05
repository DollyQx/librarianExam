<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Content & Educational Material Routes (No Login Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'handleContactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');

// Public Subjects & Topics
Route::get('/subjects', [StudentController::class, 'subjects'])->name('subjects');
Route::get('/student/subjects', [StudentController::class, 'subjects'])->name('student.subjects');

// Public PDF Study Materials & Downloads
Route::get('/materials', [StudentController::class, 'materials'])->name('materials');
Route::get('/student/materials', [StudentController::class, 'materials'])->name('student.materials');
Route::get('/materials/{material}/download', [StudentController::class, 'downloadMaterial'])->name('materials.download');
Route::get('/student/materials/{material}/download', [StudentController::class, 'downloadMaterial'])->name('student.materials.download');

// Public YouTube Video Lectures
Route::get('/videos', [StudentController::class, 'videos'])->name('videos');
Route::get('/student/videos', [StudentController::class, 'videos'])->name('student.videos');

// Public Quizzes & Test Series Engine
Route::get('/tests', [StudentController::class, 'tests'])->name('tests');
Route::get('/student/tests', [StudentController::class, 'tests'])->name('student.tests');
Route::get('/tests/{quiz}', [StudentController::class, 'showTest'])->name('tests.show');
Route::get('/student/tests/{quiz}', [StudentController::class, 'showTest'])->name('student.tests.show');
Route::post('/tests/{quiz}/submit', [StudentController::class, 'submitTest'])->name('tests.submit');
Route::post('/student/tests/{quiz}/submit', [StudentController::class, 'submitTest'])->name('student.tests.submit');
Route::get('/tests/{quiz}/result/{attempt}', [StudentController::class, 'result'])->name('tests.result');
Route::get('/student/tests/{quiz}/result/{attempt}', [StudentController::class, 'result'])->name('student.tests.result');

/*
|--------------------------------------------------------------------------
| Dynamic XML Sitemap
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', function () {
    $baseUrl = config('app.url', 'http://127.0.0.1:8000');
    
    $urls = [
        ['loc' => $baseUrl, 'priority' => '1.0', 'freq' => 'daily'],
        ['loc' => $baseUrl . '/subjects', 'priority' => '0.9', 'freq' => 'weekly'],
        ['loc' => $baseUrl . '/tests', 'priority' => '0.9', 'freq' => 'daily'],
        ['loc' => $baseUrl . '/materials', 'priority' => '0.8', 'freq' => 'weekly'],
        ['loc' => $baseUrl . '/videos', 'priority' => '0.8', 'freq' => 'weekly'],
        ['loc' => $baseUrl . '/about', 'priority' => '0.5', 'freq' => 'monthly'],
        ['loc' => $baseUrl . '/contact', 'priority' => '0.5', 'freq' => 'monthly'],
        ['loc' => $baseUrl . '/privacy-policy', 'priority' => '0.3', 'freq' => 'yearly'],
        ['loc' => $baseUrl . '/terms', 'priority' => '0.3', 'freq' => 'yearly'],
    ];

    // Add active quizzes to sitemap
    $activeQuizzes = \App\Models\Quiz::where('is_active', true)->get();
    foreach ($activeQuizzes as $quiz) {
        $urls[] = [
            'loc' => $baseUrl . '/tests/' . $quiz->id,
            'priority' => '0.8',
            'freq' => 'weekly'
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($urls as $item) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars($item['loc']) . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>' . $item['freq'] . '</changefreq>';
        $xml .= '<priority>' . $item['priority'] . '</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = file_exists(public_path('robots.txt')) 
        ? file_get_contents(public_path('robots.txt'))
        : "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /dashboard\nDisallow: /student/\n";

    return Response::make($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Student Portal Routes (Personalization Layer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/attempts', [StudentController::class, 'attempts'])->name('student.attempts');
    Route::get('/student/doubts', [StudentController::class, 'doubts'])->name('student.doubts');
    Route::post('/student/doubts', [StudentController::class, 'storeDoubt'])->name('student.doubts.store');
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/student/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Control Center Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::post('/students/{user}/toggle', [AdminController::class, 'toggleStudentStatus'])->name('students.toggle');

    Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AdminController::class, 'deleteSubject'])->name('subjects.delete');

    Route::get('/topics', [AdminController::class, 'topics'])->name('topics');
    Route::post('/topics', [AdminController::class, 'storeTopic'])->name('topics.store');
    Route::put('/topics/{topic}', [AdminController::class, 'updateTopic'])->name('topics.update');
    Route::delete('/topics/{topic}', [AdminController::class, 'deleteTopic'])->name('topics.delete');

    Route::get('/materials', [AdminController::class, 'materials'])->name('materials');
    Route::post('/materials', [AdminController::class, 'storeMaterial'])->name('materials.store');
    Route::put('/materials/{material}', [AdminController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('/materials/{material}', [AdminController::class, 'deleteMaterial'])->name('materials.delete');

    Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
    Route::post('/videos', [AdminController::class, 'storeVideo'])->name('videos.store');
    Route::put('/videos/{video}', [AdminController::class, 'updateVideo'])->name('videos.update');
    Route::delete('/videos/{video}', [AdminController::class, 'deleteVideo'])->name('videos.delete');

    Route::get('/quizzes', [AdminController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes', [AdminController::class, 'storeQuiz'])->name('quizzes.store');
    Route::put('/quizzes/{quiz}', [AdminController::class, 'updateQuiz'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminController::class, 'deleteQuiz'])->name('quizzes.delete');

    Route::get('/quizzes/{quiz}/questions', [AdminController::class, 'questions'])->name('questions');
    Route::post('/quizzes/{quiz}/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::put('/questions/{question}', [AdminController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}', [AdminController::class, 'deleteQuestion'])->name('questions.delete');

    Route::get('/attempts', [AdminController::class, 'attempts'])->name('attempts');

    Route::get('/doubts', [AdminController::class, 'doubts'])->name('doubts');
    Route::post('/doubts/{doubt}/reply', [AdminController::class, 'replyDoubt'])->name('doubts.reply');
});
