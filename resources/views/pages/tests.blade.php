@extends('layouts.app')

@section('title', 'All Tests | Bihar LET & Bihar Librarian Exam')

@section('content')
<div class="container py-5">
    <div class="text-center max-w-700 mx-auto mb-4">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">
            <i class="fas fa-list-ol me-1"></i> ऑल टेस्ट्स एवं मॉक सीरीज़
        </span>
        <h1 class="fw-bold text-dark mb-2">Bihar LET एवं Bihar Librarian Exam Tests</h1>
        <p class="text-muted">अपनी परीक्षा की तैयारी को परखें। रियल-टाइम टाइमर, ऑटो-ग्रैडिंग एवं विस्तृत उत्तर व्याख्या के साथ।</p>
    </div>

    <!-- FILTER CATEGORY BUTTONS -->
    <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
        <a href="{{ url('/tests') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 fw-bold">
            सभी क्विज़ (All Tests)
        </a>
        <a href="{{ url('/tests?category=bihar-let') }}" class="btn {{ request('category') == 'bihar-let' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 fw-bold">
            Bihar LET Exam
        </a>
        <a href="{{ url('/tests?category=bihar-librarian') }}" class="btn {{ request('category') == 'bihar-librarian' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 fw-bold">
            Bihar Librarian Exam
        </a>
    </div>

    <div class="row g-4">
        @forelse($quizzes as $quiz)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 border-top border-4 {{ $quiz->isMembershipRequired() ? 'border-warning' : 'border-primary' }}">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
                                    {{ $quiz->subject->name ?? 'Bihar Exam' }}
                                </span>
                                @if($quiz->isMembershipRequired())
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="fas fa-lock me-1"></i> Membership</span>
                                @else
                                    <span class="badge bg-success text-white fw-bold px-2 py-1">FREE</span>
                                @endif
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($quiz->description, 100) }}</p>

                            <div class="p-3 bg-light rounded-3 mb-4">
                                <div class="row g-2 text-center small">
                                    <div class="col-4 border-end">
                                        <div class="fw-bold text-dark">{{ $quiz->questions_count }}</div>
                                        <div class="text-muted extra-small">Questions</div>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="fw-bold text-dark">{{ (int)($quiz->questions_count * $quiz->marks_per_question) }}</div>
                                        <div class="text-muted extra-small">Marks</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-dark">{{ $quiz->duration_minutes }} मि.</div>
                                        <div class="text-muted extra-small">Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($quiz->isMembershipRequired())
                            <a href="{{ route('membership.index') }}" class="btn btn-warning w-100 fw-bold text-dark rounded-3">
                                <i class="fas fa-crown me-1"></i> Membership लें
                            </a>
                        @else
                            <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                                <i class="fas fa-play me-2"></i> Start Test
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">कोई क्विज़ उपलब्ध नहीं है।</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
