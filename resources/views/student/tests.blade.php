@extends('layouts.app')

@section('title', 'All Mock Tests | Student Portal')

@section('content')
<div class="container py-4">
    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Examination Series</span>
        <h3 class="fw-bold text-dark mb-2">Available Librarian Mock Tests</h3>
        <p class="text-muted">Take real-time timed mock tests with instant result generation.</p>
    </div>

    <div class="row g-4">
        @forelse($quizzes as $quiz)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 border-top border-4 border-primary bg-white">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold text-uppercase px-3 py-2 rounded-pill">
                                {{ ucfirst($quiz->type) }} Test
                            </span>
                            <span class="text-muted small"><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }} Mins</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($quiz->description, 100) }}</p>

                        <div class="p-3 bg-light rounded-3 mb-4">
                            <div class="row g-2 text-center small">
                                <div class="col-6 border-end">
                                    <div class="fw-bold text-dark">{{ $quiz->questions_count }}</div>
                                    <div class="text-muted extra-small">Questions</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-dark">{{ $quiz->pass_percentage }}%</div>
                                    <div class="text-muted extra-small">Passing Mark</div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                            <i class="fas fa-play me-2"></i> Attempt Test Now
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">No active tests available currently.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
