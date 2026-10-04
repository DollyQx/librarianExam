@extends('layouts.app')

@section('title', 'Study Subjects | Student Portal')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-dark mb-4"><i class="fas fa-book text-primary me-2"></i> Librarian Exam Subjects</h3>

    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-icon bg-primary text-white">
                            <i class="{{ $subject->icon_class }}"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ $subject->name }}</h5>
                            <small class="text-muted">{{ $subject->topics->count() }} Topics</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">{{ $subject->description }}</p>

                    <h6 class="fw-bold text-dark small mb-2">Chapters / Topics:</h6>
                    <div class="list-group list-group-flush mb-4">
                        @foreach($subject->topics as $topic)
                            <div class="list-group-item bg-transparent px-0 border-0 py-1 small">
                                <i class="fas fa-check text-success me-2"></i> {{ $topic->name }}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('student.materials', ['subject_id' => $subject->id]) }}" class="btn btn-outline-danger btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fas fa-file-pdf me-1"></i> PDFs
                        </a>
                        <a href="{{ route('student.videos', ['subject_id' => $subject->id]) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fab fa-youtube me-1"></i> Videos
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-muted">No subjects found.</div>
        @endforelse
    </div>
</div>
@endsection
