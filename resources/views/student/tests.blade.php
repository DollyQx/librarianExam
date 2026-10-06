@extends('layouts.app')

@section('title', 'All Tests & Quiz Series | Bihar LET & Bihar Librarian Exam')
@section('meta_description', 'Bihar LET एवं Bihar Librarian परीक्षा के लिए निशुल्क एवं प्रीमियम मॉक टेस्ट्स और क्विज़ सीरीज।')

@section('content')
<div class="container py-4">
    <div class="text-center max-w-700 mx-auto mb-4">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">
            <i class="fas fa-list-ol me-1"></i> ऑल टेस्ट्स एवं मॉक सीरीज़
        </span>
        <h1 class="fw-bold text-dark mb-2 fs-3">Bihar LET एवं Bihar Librarian Exam Tests</h1>
        <p class="text-muted small">अपनी परीक्षा की तैयारी को परखें। रियल-टाइम टाइमर, ऑटो-ग्रैडिंग एवं विस्तृत उत्तर व्याख्या के साथ।</p>
    </div>

    <!-- Category Filter Tabs -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
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
                <div class="card h-100 border-0 shadow-sm rounded-4 border-top border-4 {{ $quiz->isMembershipRequired() ? 'border-warning' : 'border-primary' }} bg-white">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1 rounded-pill">
                                    {{ $quiz->subject->name ?? 'Bihar Exam' }}
                                </span>
                                @if($quiz->isMembershipRequired())
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="fas fa-lock me-1"></i> Membership</span>
                                @else
                                    <span class="badge bg-success text-white fw-bold px-2 py-1">FREE</span>
                                @endif
                            </div>
                            <h3 class="fw-bold text-dark mb-2 fs-5">{{ $quiz->title }}</h3>
                            <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($quiz->description, 90) }}</p>

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

                        @if($quiz->isMembershipRequired() && (!Auth::check() || !Auth::user()->hasActiveMembership()))
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
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-vial fs-1 text-muted mb-3 d-block"></i>
                <p>कोई क्विज़ उपलब्ध नहीं है।</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $quizzes->appends(request()->query())->links() }}
    </div>
</div>
@endsection
