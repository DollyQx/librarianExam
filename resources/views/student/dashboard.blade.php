@extends('layouts.app')

@section('title', 'Student Dashboard | ' . config('app.name', 'Librarian Exam Prep'))

@section('content')
<div class="container py-4">
    <!-- Welcome Header Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-warning text-dark fw-bold mb-2"><i class="fas fa-graduation-cap me-1"></i> Student Portal</span>
                <h2 class="fw-bold mb-1">Welcome back, {{ $user->name }}!</h2>
                <p class="mb-0 text-white-50">Prepare for KVS, NVS, EMRS, UGC-NET, and State Librarian exams with free practice tests and study materials.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('student.tests') }}" class="btn btn-warning fw-bold px-4 py-2 text-dark rounded-3 shadow">
                    <i class="fas fa-play me-2"></i> Take Mock Test
                </a>
            </div>
        </div>
    </div>

    <!-- 5 Obvious Primary Student Actions Grid -->
    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-rocket text-primary me-2"></i> Primary Action Hub</h5>
    <div class="row g-3 mb-4">
        <!-- Action 1: Continue Learning -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.subjects') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-primary">
                    <div class="brand-icon bg-primary text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">1. Continue Learning</h6>
                    <small class="text-muted extra-small d-block">Browse Subjects & Topics</small>
                </div>
            </a>
        </div>

        <!-- Action 2: Practice Quiz -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.tests') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-info">
                    <div class="brand-icon bg-info text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">2. Practice Quiz</h6>
                    <small class="text-muted extra-small d-block">Subject-wise Quizzes</small>
                </div>
            </a>
        </div>

        <!-- Action 3: Take Mock Test -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.tests') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-success">
                    <div class="brand-icon bg-success text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-vial"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">3. Take Mock Test</h6>
                    <small class="text-muted extra-small d-block">Timed Full Mock Papers</small>
                </div>
            </a>
        </div>

        <!-- Action 4: View Results -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.attempts') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-warning">
                    <div class="brand-icon bg-warning text-dark mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-poll-h"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">4. View Results</h6>
                    <small class="text-muted extra-small d-block">Scorecard & Attempt Log</small>
                </div>
            </a>
        </div>

        <!-- Action 5: Ask Doubt -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.doubts') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-danger">
                    <div class="brand-icon bg-danger text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">5. Ask Doubt</h6>
                    <small class="text-muted extra-small d-block">Mentorship & Q&A</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Dashboard Body Grid -->
    <div class="row g-4">
        <!-- Main Column -->
        <div class="col-lg-8">
            <!-- Featured Mock Tests Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-vial text-primary me-2"></i> Featured Exam Series</h5>
                    <a href="{{ route('student.tests') }}" class="btn btn-sm btn-outline-primary fw-bold">View All</a>
                </div>
                <div class="row g-3">
                    @forelse($availableTests as $quiz)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column">
                                <span class="badge bg-primary bg-opacity-10 text-primary w-fit-content mb-2">{{ ucfirst($quiz->type) }} Test</span>
                                <h6 class="fw-bold text-dark mb-1">{{ $quiz->title }}</h6>
                                <p class="text-muted extra-small mb-3 flex-grow-1">{{ Str::limit($quiz->description, 70) }}</p>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top extra-small text-muted mb-3">
                                    <span><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }} Mins</span>
                                    <span><i class="fas fa-percentage text-success me-1"></i> {{ $quiz->pass_percentage }}% Pass</span>
                                </div>
                                <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary btn-sm fw-bold w-100">
                                    Attempt Test Now
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No mock tests available currently.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Results History -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-success me-2"></i> My Recent Scorecards</h5>
                    <a href="{{ route('student.attempts') }}" class="btn btn-sm btn-outline-success fw-bold">Full History</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Test Title</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttempts as $attempt)
                                <tr>
                                    <td class="fw-bold text-dark">{{ Str::limit($attempt->quiz->title ?? 'Test', 30) }}</td>
                                    <td>{{ $attempt->marks_obtained }} / {{ $attempt->max_marks }}</td>
                                    <td>
                                        <span class="badge {{ $attempt->percentage >= ($attempt->quiz->pass_percentage ?? 40) ? 'bg-success' : 'bg-danger' }}">
                                            {{ number_format($attempt->percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('student.tests.result', [$attempt->quiz_id, $attempt->id]) }}" class="btn btn-sm btn-outline-primary py-0">
                                            View Solution
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">You haven't attempted any tests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Latest Study PDFs -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-pdf text-danger me-2"></i> Study PDFs</h6>
                    <a href="{{ route('student.materials') }}" class="btn btn-sm text-danger p-0 fw-bold">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentMaterials as $pdf)
                        <div class="list-group-item px-0 py-2 border-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-file-pdf text-danger fs-5"></i>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark extra-small text-truncate mb-0">{{ $pdf->title }}</h6>
                                    <small class="text-muted extra-small">{{ $pdf->subject->name ?? 'General' }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted extra-small">No PDFs available.</div>
                    @endforelse
                </div>
            </div>

            <!-- Doubts Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-question-circle text-warning me-2"></i> Academic Doubts?</h6>
                <p class="text-muted extra-small mb-3">Submit your question to receive detailed mentorship replies directly from our teachers.</p>
                <a href="{{ route('student.doubts') }}" class="btn btn-warning btn-sm w-100 fw-bold text-dark">
                    <i class="fas fa-comment-dots me-1"></i> Ask a Doubt Free
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .col-lg-2-4 {
        flex: 0 0 auto;
        width: 20%;
    }
    @media (max-width: 991.98px) {
        .col-lg-2-4 {
            width: 33.333%;
        }
    }
    @media (max-width: 575.98px) {
        .col-lg-2-4 {
            width: 50%;
        }
    }
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
</style>
@endsection
