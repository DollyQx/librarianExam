@extends('layouts.app')

@section('title', 'BIHAR LET & Bihar Librarian Exam Prep | Bihar Exam Portal')

@section('content')

<!-- 1. HERO SECTION -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-3">
                    <i class="fas fa-graduation-cap me-1"></i> संचालक: Sumit Verma
                </span>
                <h1 class="display-6 fw-bold mb-3 text-dark">
                    BIHAR LET & Bihar Librarian Exam की तैयारी करें
                </h1>
                <p class="lead mb-4 text-muted fs-5">
                    Mock Tests, Quiz, PDF Notes और Video Lectures के साथ अपनी तैयारी करें।
                </p>

                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mb-4">
                    <a href="#free-tests" class="btn btn-brand-primary btn-lg fw-bold px-4 py-3">
                        <i class="fas fa-play-circle me-2"></i> Start Free Test
                    </a>
                    <a href="{{ route('membership.index') }}" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3">
                        <i class="fas fa-crown me-2"></i> ₹49 Membership लें
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ url('/tests?category=bihar-let') }}" class="badge bg-light text-dark border p-2 px-3 text-decoration-none fw-semibold">
                        <i class="fas fa-check-circle text-success me-1"></i> Bihar LET
                    </a>
                    <a href="{{ url('/tests?category=bihar-librarian') }}" class="badge bg-light text-dark border p-2 px-3 text-decoration-none fw-semibold">
                        <i class="fas fa-check-circle text-success me-1"></i> Bihar Librarian
                    </a>
                </div>
            </div>

            <!-- COMMUNITY CONNECT -->
            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border shadow-sm text-start">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-users text-primary me-2"></i> कम्युनिटी से जुड़ें
                    </h5>
                    
                    <div class="d-flex flex-column gap-3 mb-3">
                        <a href="https://t.me/SssVvv8271" target="_blank" class="btn btn-info text-white fw-bold py-2 px-3 rounded-3 d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fab fa-telegram fs-4 me-2 align-middle"></i>
                                <span class="align-middle">Telegram Group (SssVvv8271)</span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="https://youtube.com/@choicestudyjunction8380" target="_blank" class="btn btn-danger text-white fw-bold py-2 px-3 rounded-3 d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fab fa-youtube fs-4 me-2 align-middle"></i>
                                <span class="align-middle">Choice Study Junction (YouTube)</span>
                            </div>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. FREE MOCK TESTS SECTION -->
<section id="free-tests" class="py-5 bg-light border-bottom">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <span class="badge bg-success px-3 py-1 mb-2">100% Free</span>
                <h2 class="fw-bold text-dark mb-0">Free Mock Tests</h2>
            </div>
            <a href="{{ url('/tests') }}" class="btn btn-brand-outline mt-3 mt-md-0 fw-bold">
                सभी मॉक टेस्ट देखें <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($popularTests as $quiz)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
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

                                <h5 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h5>
                                <p class="text-muted small mb-3">{{ Str::limit($quiz->description, 90) }}</p>

                                <div class="row g-2 text-center text-muted small py-2 bg-light rounded-3 mb-3">
                                    <div class="col-4">
                                        <div class="fw-bold text-dark">{{ $quiz->questions_count ?? $quiz->questions()->count() }}</div>
                                        <div class="extra-small">Questions</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-dark">{{ (int)($quiz->questions_count ?? $quiz->questions()->count() * $quiz->marks_per_question) }}</div>
                                        <div class="extra-small">Marks</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-dark">{{ $quiz->duration_minutes }}</div>
                                        <div class="extra-small">Minutes</div>
                                    </div>
                                </div>
                            </div>

                            @if($quiz->isMembershipRequired())
                                <a href="{{ route('membership.index') }}" class="btn btn-warning w-100 fw-bold rounded-3 text-dark">
                                    <i class="fas fa-crown me-1"></i> Membership लें
                                </a>
                            @else
                                <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                                    <i class="fas fa-play me-1"></i> Start Test
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4 text-muted">
                    <p>कोई Free Mock Test उपलब्ध नहीं है।</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 3. PREMIUM MEMBERSHIP SECTION -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-2">
        <div class="p-4 p-md-5 rounded-4 bg-gradient text-dark border border-warning shadow-sm" style="background: #fffbeb;">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3 fs-6">
                        <i class="fas fa-star me-1"></i> Studyly Membership
                    </span>
                    <h2 class="fw-bold display-6 mb-3 text-dark">
                        पूरी तैयारी, सिर्फ ₹49 में!
                    </h2>
                    <p class="fs-5 text-secondary mb-4">
                        30 दिनों के लिए Full Mock Tests, Quiz Series, PDF Notes, Video Lectures और बहुत कुछ।
                    </p>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success fs-5"></i> All Full Mock Tests & Quiz Series Access</li>
                        <li class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success fs-5"></i> All PDF Notes & Class Lecture Materials</li>
                        <li class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success fs-5"></i> Active membership automatically unlocks future new tests</li>
                    </ul>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="p-4 bg-white rounded-4 shadow-sm border border-warning">
                        <div class="text-muted small fw-bold text-uppercase">Monthly Plan</div>
                        <div class="display-4 fw-extrabold text-dark my-2">₹49</div>
                        <div class="text-muted small mb-4">30 Days Validity</div>
                        <a href="{{ route('membership.index') }}" class="btn btn-warning btn-lg w-100 fw-bold text-dark shadow-sm">
                            अभी Membership लें
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. EXAM INFORMATION SECTIONS -->
<section class="py-5 bg-light border-bottom">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="text-primary fw-bold text-uppercase small">परीक्षा गाइड</span>
            <h2 class="fw-bold text-dark">Bihar LET & Librarian Exam Overview</h2>
        </div>

        <div class="row g-4">
            <!-- Exam Pattern -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="brand-icon bg-primary text-white mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Exam Pattern</h5>
                        <p class="text-muted small mb-0"> Objective Multiple Choice Questions (MCQ) based on Library & Information Science and General Aptitude.</p>
                    </div>
                </div>
            </div>

            <!-- Syllabus -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="brand-icon bg-success text-white mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="fas fa-book"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Syllabus</h5>
                        <p class="text-muted small mb-0">Library Classification, Cataloguing, Library Management, Information Technology, & General Awareness.</p>
                    </div>
                </div>
            </div>

            <!-- Eligibility -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="brand-icon bg-warning text-dark mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Eligibility</h5>
                        <p class="text-muted small mb-0">BLIS (Bachelor of Library Science) / MLIS or Diploma in Library Science from recognized university.</p>
                    </div>
                </div>
            </div>

            <!-- Practice MCQs -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="brand-icon bg-info text-white mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Practice MCQs</h5>
                        <p class="text-muted small mb-0">Unit-wise practice sets and previous year solved questions with detailed explanations.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i> अंतिम जानकारी के लिए official notification देखें.
            </small>
        </div>
    </div>
</section>

<!-- 5. FAQ SECTION -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="text-primary fw-bold text-uppercase small">अक्सर पूछे जाने वाले प्रश्न</span>
            <h2 class="fw-bold text-dark">Frequently Asked Questions</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Membership कितने दिनों की है?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                30 दिन।
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Membership कितने की है?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                ₹49.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Membership में क्या मिलेगा?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Full Mock Tests, Quiz Series, PDF Notes, Video Lectures और Membership वाले सभी Tests.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                क्या कुछ Tests Free हैं?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                हाँ.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Payment के बाद access कब मिलेगा?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Successful payment के बाद तुरंत.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                क्या membership renew की जा सकती है?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                हाँ.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
