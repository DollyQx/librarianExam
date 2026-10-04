@extends('layouts.admin')

@section('title', 'Admin Overview | Librarian Prep')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Stats Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Students</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_students']) }}</h2>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Subjects</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_subjects']) }}</h2>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">PDF Materials</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_materials']) }}</h2>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-file-pdf"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">YouTube Videos</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_videos']) }}</h2>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Quizzes</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_quizzes']) }}</h2>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-file-signature"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Student Attempts</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_attempts']) }}</h2>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card card-stat p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Pending Doubts</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['pending_doubts']) }}</h2>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Test Attempts -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-primary me-2"></i> Recent Student Attempts</h5>
                <a href="{{ route('admin.attempts') }}" class="btn btn-sm btn-outline-primary fw-bold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttempts as $attempt)
                            <tr>
                                <td class="fw-semibold">{{ $attempt->user->name ?? 'Student' }}</td>
                                <td class="small">{{ Str::limit($attempt->quiz->title ?? 'Test', 25) }}</td>
                                <td>{{ $attempt->marks_obtained }} / {{ $attempt->max_marks }}</td>
                                <td>
                                    <span class="badge {{ $attempt->percentage >= ($attempt->quiz->pass_percentage ?? 40) ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($attempt->percentage, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-muted border">{{ ucfirst($attempt->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No student attempts recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Doubts Queue -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-question-circle text-warning me-2"></i> Pending Doubts</h5>
                <a href="{{ route('admin.doubts') }}" class="btn btn-sm btn-outline-warning fw-bold">Open Queue</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($pendingDoubtsList as $doubt)
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-bold text-dark mb-1">{{ $doubt->title }}</h6>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </div>
                        <p class="text-muted extra-small mb-2">{{ Str::limit($doubt->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center extra-small text-muted">
                            <span><i class="fas fa-user me-1"></i> {{ $doubt->user->name ?? 'Student' }}</span>
                            <span>{{ $doubt->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">No pending student doubts!</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
