@extends('layouts.app')

@section('title', 'Free Librarian Exam Prep | Mock Tests, PDFs & Video Lectures')

@section('content')

<!-- 2. HERO SECTION -->
<section class="py-5 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3">
                    <i class="fas fa-star me-1"></i> 100% Free Learning Portal
                </span>
                <h1 class="display-4 fw-extrabold mb-3 text-white" style="line-height: 1.15;">
                    Master Your <span class="text-warning">Librarian Exam</span> Preparation Free
                </h1>
                <p class="lead mb-4 text-white-50" style="font-size: 1.15rem;">
                    Comprehensive subject-wise study materials, chapter PDFs, high-definition YouTube video lectures, and timed full mock tests for KVS, NVS, EMRS, UGC-NET & State Librarian exams.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-dark rounded-3 shadow">
                        <i class="fas fa-rocket me-2"></i> Start Preparing Free
                    </a>
                    <a href="{{ url('/tests') }}" class="btn btn-outline-light btn-lg fw-bold px-4 py-3 rounded-3">
                        <i class="fas fa-vial me-2"></i> Take Free Mock Test
                    </a>
                </div>
                <div class="mt-4 pt-2 d-flex align-items-center justify-content-center justify-content-lg-start gap-4 text-white-50 small">
                    <div><i class="fas fa-check-circle text-success me-1"></i> No Credit Card Required</div>
                    <div><i class="fas fa-check-circle text-success me-1"></i> Automatic Scoring</div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 shadow-lg">
                    <div class="text-center mb-3">
                        <div class="brand-icon bg-warning text-dark mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.8rem;">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-1">Targeted Exams</h4>
                        <p class="text-white-50 small mb-0">Prepare for top Librarian recruitment tests</p>
                    </div>
                    <div class="row g-2 text-start">
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                                <div class="fw-bold"><i class="fas fa-book-open text-warning me-2"></i> KVS / NVS</div>
                                <small class="text-white-50">Librarian Posts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                                <div class="fw-bold"><i class="fas fa-graduation-cap text-warning me-2"></i> UGC NET</div>
                                <small class="text-white-50">Library Science</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                                <div class="fw-bold"><i class="fas fa-university text-warning me-2"></i> EMRS</div>
                                <small class="text-white-50">School Librarian</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-10 text-white border border-white border-opacity-10">
                                <div class="fw-bold"><i class="fas fa-building text-warning me-2"></i> State PSC</div>
                                <small class="text-white-50">State Librarian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. LIBRARIAN EXAM PREPARATION INTRODUCTION -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="pe-lg-3">
                    <span class="text-primary fw-bold text-uppercase tracking-wider small">Educational Excellence</span>
                    <h2 class="display-6 fw-bold text-dark mt-2 mb-3">Dedicated Exam Portal for Library Science Aspirants</h2>
                    <p class="text-muted">
                        Library & Information Science competitive examinations require in-depth mastery of classification schemes (DDC, UDC, CC), cataloguing rules (AACR-2, CCC), library automation, information retrieval systems, and digital libraries.
                    </p>
                    <p class="text-muted">
                        Our platform is engineered specifically to help students revise core concepts, test their accuracy through real-time timed test simulations, download curated PDF notes, and clear doubts with expert replies—all at zero cost.
                    </p>
                    <div class="row g-3 mt-2">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                                <i class="fas fa-file-pdf text-danger fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Subject PDFs</h6>
                                    <small class="text-muted">Free Downloadable Notes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                                <i class="fas fa-stopwatch text-warning fs-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Timed Engine</h6>
                                    <small class="text-muted">Instant Scorecard</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 rounded-4 shadow-sm bg-light border border-light">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80" alt="Library Science Exam Prep" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. SUBJECTS / CATEGORIES -->
<section class="py-5 bg-light">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-primary fw-bold text-uppercase small">Curriculum Breakdown</span>
            <h2 class="fw-bold text-dark mb-2">Core Library Science Subjects</h2>
            <p class="text-muted">Explore structured subjects designed according to the latest Librarian exam syllabi.</p>
        </div>

        <div class="row g-4">
            @forelse($subjects as $subject)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="brand-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="{{ $subject->icon_class ?: 'fas fa-book' }}"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $subject->name }}</h5>
                                    <span class="badge bg-light text-muted border">{{ $subject->topics_count }} Topics</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-4">{{ Str::limit($subject->description, 100) }}</p>
                            <a href="{{ url('/subjects') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold w-100">
                                Explore Topics <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Subjects loading...</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 5. POPULAR TESTS / MOCK TESTS -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div>
                <span class="text-primary fw-bold text-uppercase small">Test Series</span>
                <h2 class="fw-bold text-dark mb-0">Popular Mock Tests & Practice Quizzes</h2>
            </div>
            <a href="{{ url('/tests') }}" class="btn btn-brand-outline mt-3 mt-md-0">
                View All Tests <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($popularTests as $quiz)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 border-top border-4 border-primary">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold uppercase px-3 py-2 rounded-pill">
                                    {{ ucfirst($quiz->type) }} Test
                                </span>
                                <span class="text-muted small"><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }} Mins</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($quiz->description, 90) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted small mb-4">
                                <div><i class="fas fa-question-circle text-info me-1"></i> MCQs Included</div>
                                <div><i class="fas fa-check text-success me-1"></i> {{ $quiz->pass_percentage }}% Pass Mark</div>
                            </div>

                            <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                                Attempt Test Now
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No mock tests available currently.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 6. STUDY MATERIAL SECTION -->
<section class="py-5 bg-light">
    <div class="container py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <span class="text-primary fw-bold text-uppercase small">Free PDF Repository</span>
                <h2 class="fw-bold text-dark mt-2 mb-3">High Quality Revision PDFs & Notes</h2>
                <p class="text-muted mb-4">
                    Download concise, topic-wise study material prepared for easy reading on smartphones and tablets. No payment required.
                </p>
                <a href="{{ route('student.materials') }}" class="btn btn-brand-primary">
                    <i class="fas fa-file-pdf me-2"></i> Browse All PDFs
                </a>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    @forelse($recentMaterials as $pdf)
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border d-flex align-items-start gap-3">
                                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-4">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $pdf->title }}</h6>
                                    <span class="badge bg-light text-secondary border mb-2">{{ $pdf->subject->name ?? 'General' }}</span>
                                    <div class="text-muted extra-small">
                                        <i class="fas fa-download me-1"></i> {{ $pdf->downloads_count }} Downloads
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3 text-muted">PDF Materials loading...</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. VIDEO CLASSES SECTION -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-primary fw-bold text-uppercase small">Video Lectures</span>
            <h2 class="fw-bold text-dark mb-2">Embedded Video Classes</h2>
            <p class="text-muted">Watch expert video explanations for intricate Library Science topics directly on our platform.</p>
        </div>

        <div class="row g-4">
            @forelse($recentVideos as $video)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="{{ $video->title }}" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $video->subject->name ?? 'Video' }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $video->title }}</h6>
                            <p class="text-muted small mb-0">{{ Str::limit($video->description, 80) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-3 text-muted">Video classes updating...</div>
            @endforelse
        </div>
    </div>
</section>

<!-- 8. WHY STUDENTS SHOULD USE THIS PLATFORM -->
<section class="py-5 text-white" style="background-color: #0f172a;">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-warning fw-bold text-uppercase small">Platform Advantages</span>
            <h2 class="fw-bold text-white mb-2">Why Choose {{ config('app.name', 'Librarian Exam Prep') }}?</h2>
            <p class="text-white-50">Built with student-first philosophy for maximum learning efficiency.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 h-100 text-center">
                    <div class="brand-icon bg-warning text-dark mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h5 class="fw-bold text-white">100% Free Forever</h5>
                    <p class="text-white-50 small mb-0">No hidden fees, no subscriptions, no premium paywalls. Everything is accessible for free.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 h-100 text-center">
                    <div class="brand-icon bg-info text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="fw-bold text-white">Instant Auto Grading</h5>
                    <p class="text-white-50 small mb-0">Submit tests and get detailed scorecards instantly with accuracy stats, time spent, and explanations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 h-100 text-center">
                    <div class="brand-icon bg-success text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="fw-bold text-white">Doubts Support</h5>
                    <p class="text-white-50 small mb-0">Ask questions on tricky MCQs or topics and receive clear answers directly from teachers/admins.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. SIMPLE STATISTICS SECTION -->
<section class="py-5 bg-primary text-white">
    <div class="container py-2">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-extrabold mb-1">{{ number_format($stats['total_students']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">Registered Students</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-extrabold mb-1">{{ number_format($stats['total_subjects']) }}</h2>
                <p class="text-white-50 mb-0 font-heading">Librarian Subjects</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-extrabold mb-1">{{ number_format($stats['total_materials']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">Free PDF Notes</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-extrabold mb-1">{{ number_format($stats['total_tests']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">Active Mock Tests</p>
            </div>
        </div>
    </div>
</section>

<!-- 10. FAQ SECTION -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-primary fw-bold text-uppercase small">Got Questions?</span>
            <h2 class="fw-bold text-dark mb-2">Frequently Asked Questions</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Is this platform really 100% free for students?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Yes! All study material, mock tests, video lectures, and doubt support are completely free for candidates preparing for Librarian competitive exams.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Which exams are covered on {{ config('app.name', 'Librarian Exam Prep') }}?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Our platform covers KVS Librarian, NVS Librarian, EMRS School Librarian, UGC-NET Library Science, and all State Public Service Commission Librarian competitive recruitment exams.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How does the auto-grading system work?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                When you submit a test, our server automatically evaluates your responses, calculates marks according to positive and negative marking rules, and displays your score, accuracy, and detailed question explanations.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
