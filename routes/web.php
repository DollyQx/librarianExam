<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'handleContactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/subjects', [PublicController::class, 'subjects'])->name('subjects');
Route::get('/tests', [PublicController::class, 'tests'])->name('tests');

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
| Student Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/subjects', [StudentController::class, 'subjects'])->name('student.subjects');
    Route::get('/student/materials', [StudentController::class, 'materials'])->name('student.materials');
    Route::get('/student/materials/{material}/download', [StudentController::class, 'downloadMaterial'])->name('student.materials.download');
    Route::get('/student/videos', [StudentController::class, 'videos'])->name('student.videos');
    Route::get('/student/tests', [StudentController::class, 'tests'])->name('student.tests');
    Route::get('/student/tests/{quiz}', [StudentController::class, 'showTest'])->name('student.tests.show');
    Route::post('/student/tests/{quiz}/submit', [StudentController::class, 'submitTest'])->name('student.tests.submit');
    Route::get('/student/tests/{quiz}/result/{attempt}', [StudentController::class, 'result'])->name('student.tests.result');
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
    Route::delete('/topics/{topic}', [AdminController::class, 'deleteTopic'])->name('topics.delete');

    Route::get('/materials', [AdminController::class, 'materials'])->name('materials');
    Route::post('/materials', [AdminController::class, 'storeMaterial'])->name('materials.store');
    Route::delete('/materials/{material}', [AdminController::class, 'deleteMaterial'])->name('materials.delete');

    Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
    Route::post('/videos', [AdminController::class, 'storeVideo'])->name('videos.store');
    Route::delete('/videos/{video}', [AdminController::class, 'deleteVideo'])->name('videos.delete');

    Route::get('/quizzes', [AdminController::class, 'quizzes'])->name('quizzes');
    Route::post('/quizzes', [AdminController::class, 'storeQuiz'])->name('quizzes.store');
    Route::delete('/quizzes/{quiz}', [AdminController::class, 'deleteQuiz'])->name('quizzes.delete');

    Route::get('/quizzes/{quiz}/questions', [AdminController::class, 'questions'])->name('questions');
    Route::post('/quizzes/{quiz}/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::delete('/questions/{question}', [AdminController::class, 'deleteQuestion'])->name('questions.delete');

    Route::get('/attempts', [AdminController::class, 'attempts'])->name('attempts');

    Route::get('/doubts', [AdminController::class, 'doubts'])->name('doubts');
    Route::post('/doubts/{doubt}/reply', [AdminController::class, 'replyDoubt'])->name('doubts.reply');
});
