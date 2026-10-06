@extends('layouts.app')

@section('title', 'Student Dashboard | Bihar LET & Bihar Librarian Prep')

@section('content')
<div class="container py-4">
    <!-- Welcome & Membership Status Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4" style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <span class="badge bg-warning text-dark fw-bold mb-2">
                    <i class="fas fa-graduation-cap me-1"></i> Student Portal
                </span>
                <h2 class="fw-bold mb-1">Welcome back, {{ $user->name }}!</h2>
                <p class="mb-2 text-white-50">BIHAR LET एवं Bihar Librarian परीक्षा की संपूर्ण तैयारी Portal.</p>
                
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-20">
                    <i class="fas {{ $activeMembership ? 'fa-crown text-warning' : 'fa-info-circle text-white-50' }} fs-5"></i>
                    <div>
                        <small class="text-white-50 d-block">Studyly Membership Status:</small>
                        @if($activeMembership)
                            <span class="fw-bold text-warning">Active (Valid till {{ $activeMembership->expires_at->format('d M Y') }})</span>
                        @else
                            <span class="fw-bold text-white">No Active Membership</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-md-end">
                @if($activeMembership)
                    <a href="{{ route('tests') }}" class="btn btn-warning fw-bold px-4 py-2 text-dark rounded-3 shadow">
                        <i class="fas fa-vial me-2"></i> Take Mock Test
                    </a>
                @else
                    <a href="{{ route('membership.index') }}" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-dark rounded-3 shadow">
                        <i class="fas fa-crown me-2"></i> ₹49 में Membership लें
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Navigation Hub -->
    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-th-large text-primary me-2"></i> Study Quick Hub</h5>
    <div class="row g-3 mb-4">
        <!-- Action 1: All Tests -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('tests') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-primary">
                    <div class="brand-icon bg-primary text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-vial"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">1. All Tests</h6>
                    <small class="text-muted extra-small d-block">Mock Tests & Quizzes</small>
                </div>
            </a>
        </div>

        <!-- Action 2: PDF Notes -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('materials') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-danger">
                    <div class="brand-icon bg-danger text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">2. PDF Notes</h6>
                    <small class="text-muted extra-small d-block">Online PDF Reading</small>
                </div>
            </a>
        </div>

        <!-- Action 3: Videos -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('videos') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-success">
                    <div class="brand-icon bg-success text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-video"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">3. Videos</h6>
                    <small class="text-muted extra-small d-block">Video Lectures</small>
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
                    <h6 class="fw-bold text-dark mb-1 extra-small">4. Scorecard</h6>
                    <small class="text-muted extra-small d-block">Attempt History</small>
                </div>
            </a>
        </div>

        <!-- Action 5: Ask Doubt -->
        <div class="col-6 col-md-4 col-lg-2-4">
            <a href="{{ route('student.doubts') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 text-center transition-hover border-start border-4 border-info">
                    <div class="brand-icon bg-info text-white mx-auto mb-2" style="width: 46px; height: 46px; font-size: 1.25rem;">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1 extra-small">5. Doubts</h6>
                    <small class="text-muted extra-small d-block">Ask Mentors</small>
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
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-vial text-primary me-2"></i> Featured Mock Tests</h5>
                    <a href="{{ route('tests') }}" class="btn btn-sm btn-outline-primary fw-bold">View All</a>
                </div>
                <div class="row g-3">
                    @forelse($availableTests as $quiz)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $quiz->subject->name ?? 'General' }}</span>
                                        @if($quiz->isMembershipRequired())
                                            <span class="badge bg-warning text-dark"><i class="fas fa-lock me-1"></i> Membership</span>
                                        @else
                                            <span class="badge bg-success text-white">FREE</span>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $quiz->title }}</h6>
                                    <p class="text-muted extra-small mb-3">{{ Str::limit($quiz->description, 70) }}</p>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top extra-small text-muted mb-3">
                                        <span><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }} Mins</span>
                                        <span><i class="fas fa-question-circle text-info me-1"></i> {{ $quiz->questions_count }} Questions</span>
                                    </div>
                                    @if($quiz->isMembershipRequired() && (!$activeMembership))
                                        <a href="{{ route('membership.index') }}" class="btn btn-warning btn-sm fw-bold w-100 text-dark">
                                            <i class="fas fa-crown me-1"></i> Membership लें
                                        </a>
                                    @else
                                        <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary btn-sm fw-bold w-100">
                                            Start Test Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No tests available currently.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Results History -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-success me-2"></i> Recent Scorecards</h5>
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
            <!-- Membership Promo / Details -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-warning bg-opacity-10 border-start border-4 border-warning mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-crown text-warning me-2"></i> Studyly Membership</h6>
                <p class="text-muted extra-small mb-3">₹49 में 30 दिनों के लिए पाएं Full Mock Tests, PDF Notes और Video Lectures का अनलिमिटेड एक्सेस।</p>
                <a href="{{ route('membership.index') }}" class="btn btn-warning btn-sm w-100 fw-bold text-dark">
                    <i class="fas fa-shield-alt me-1"></i> Membership Details & Purchase
                </a>
            </div>

            <!-- Doubts Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-question-circle text-info me-2"></i> Academic Doubts?</h6>
                <p class="text-muted extra-small mb-3">Submit your question to receive mentorship replies directly from Sumit Verma sir.</p>
                <a href="{{ route('student.doubts') }}" class="btn btn-info btn-sm w-100 fw-bold text-white">
                    <i class="fas fa-comment-dots me-1"></i> Ask a Doubt
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
