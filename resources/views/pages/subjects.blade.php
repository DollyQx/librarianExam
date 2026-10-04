@extends('layouts.app')

@section('title', 'Librarian Exam Subjects & Syllabus | Librarian Prep')

@section('content')
<div class="container py-5">
    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Subject Syllabus</span>
        <h1 class="fw-bold text-dark mb-2">Library & Information Science Subjects</h1>
        <p class="text-muted">Browse subject categories and chapter topics included in KVS, NVS, EMRS, UGC-NET & State Librarian recruitment syllabus.</p>
    </div>

    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-icon bg-primary text-white">
                            <i class="{{ $subject->icon_class ?: 'fas fa-book' }}"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-0">{{ $subject->name }}</h4>
                            <small class="text-muted">{{ $subject->topics->count() }} Key Topics Included</small>
                        </div>
                    </div>
                    <p class="text-muted mb-4">{{ $subject->description }}</p>

                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list-ul text-primary me-2"></i> Included Topics:</h6>
                    <div class="list-group list-group-flush mb-4">
                        @forelse($subject->topics as $topic)
                            <div class="list-group-item bg-transparent px-0 border-0 d-flex align-items-center justify-content-between py-2">
                                <div>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="fw-semibold text-dark">{{ $topic->name }}</span>
                                </div>
                                <span class="badge bg-light text-muted border">Active Topic</span>
                            </div>
                        @empty
                            <p class="text-muted small">No topics added yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('student.materials') }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fas fa-file-pdf me-1"></i> View PDFs
                        </a>
                        <a href="{{ route('student.tests') }}" class="btn btn-primary btn-sm rounded-3 fw-bold flex-grow-1">
                            <i class="fas fa-play me-1"></i> Practice Tests
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No active subjects currently found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
