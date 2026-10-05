@extends('layouts.app')

@section('title', 'Library Science Curriculum & Subjects | ' . config('branding.name'))
@section('meta_description', 'Explore comprehensive Library & Information Science subject modules, chapter topics, downloadable notes, video lectures, and practice tests.')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-book text-primary me-2"></i> Library Science Subject Curriculum</h2>
            <p class="text-muted mb-0 small">Select a subject module to browse topics, download PDF study notes, watch video lectures, or take practice quizzes.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-icon bg-primary text-white">
                            <i class="{{ $subject->icon_class }}"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-0 fs-5">{{ $subject->name }}</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $subject->topics->count() }} Chapter Topics</span>
                        </div>
                    </div>

                    <p class="text-muted small mb-3">{{ $subject->description }}</p>

                    <!-- Topics Hierarchy -->
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold text-dark small mb-2"><i class="fas fa-layer-group text-primary me-1"></i> Topics Covered:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($subject->topics as $topic)
                                <span class="badge bg-white text-dark border shadow-sm p-2">
                                    <i class="fas fa-bookmark text-warning me-1"></i> {{ $topic->name }}
                                </span>
                            @empty
                                <small class="text-muted">General curriculum module.</small>
                            @endforelse
                        </div>
                    </div>

                    <!-- Direct Resources Quick Actions -->
                    <div class="mt-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('materials', ['subject_id' => $subject->id]) }}" class="btn btn-outline-danger btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fas fa-file-pdf me-1"></i> Notes ({{ $subject->studyMaterials->count() }})
                        </a>
                        <a href="{{ route('videos', ['subject_id' => $subject->id]) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fab fa-youtube me-1"></i> Videos ({{ $subject->videos->count() }})
                        </a>
                        <a href="{{ route('tests', ['subject_id' => $subject->id]) }}" class="btn btn-outline-success btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fas fa-tasks me-1"></i> Tests ({{ $subject->quizzes->count() }})
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-book-open fs-1 text-muted mb-3 d-block"></i>
                <p>No subjects published yet. Please check back soon!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
