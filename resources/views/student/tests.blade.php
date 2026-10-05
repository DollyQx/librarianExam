@extends('layouts.app')

@section('title', 'Free Librarian Mock Tests & Test Series | ' . config('branding.name'))
@section('meta_description', 'Practice free online mock tests, subject-wise test series, and topic quizzes for Librarian competitive exams with instant scoring and detailed answer explanations.')

@section('content')
<div class="container py-4">
    <div class="text-center max-w-700 mx-auto mb-4">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Examination Series</span>
        <h2 class="fw-bold text-dark mb-2">Free Librarian Mock Tests & Practice Series</h2>
        <p class="text-muted">Take real-time timed mock tests with instant result generation and step-by-step explanations. No mandatory login required to practice!</p>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">
        <a href="{{ route('tests') }}" class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-bold">
            <i class="fas fa-th-list me-1"></i> All Tests
        </a>
        <a href="{{ route('tests', ['type' => 'mock']) }}" class="btn {{ request('type') === 'mock' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-bold">
            <i class="fas fa-trophy me-1"></i> Full Mock Tests
        </a>
        <a href="{{ route('tests', ['type' => 'subject']) }}" class="btn {{ request('type') === 'subject' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-bold">
            <i class="fas fa-book me-1"></i> Subject Tests
        </a>
        <a href="{{ route('tests', ['type' => 'topic']) }}" class="btn {{ request('type') === 'topic' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-bold">
            <i class="fas fa-bookmark me-1"></i> Topic Quizzes
        </a>
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
                        <h3 class="fw-bold text-dark mb-2 fs-5">{{ $quiz->title }}</h3>
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

                        <a href="{{ route('tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                            <i class="fas fa-play me-2"></i> Attempt Test Now
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-vial fs-1 text-muted mb-3 d-block"></i>
                <p>No tests available in this category yet. Check back soon!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $quizzes->appends(request()->query())->links() }}
    </div>
</div>
@endsection
