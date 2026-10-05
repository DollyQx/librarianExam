@extends('layouts.app')

@section('title', config('branding.seo.title'))

@section('content')

<!-- 1. HERO SECTION -->
<section class="py-5 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3 fs-6">
                    <i class="fas fa-graduation-cap me-1"></i> बिहार स्पेशल परीक्षा पोर्टल
                </span>
                <h1 class="display-5 fw-extrabold mb-3 text-white" style="line-height: 1.2;">
                    {{ config('branding.name', 'BIHAR LET/Librarian Exam') }}
                </h1>
                <p class="lead mb-4 text-white-50 fs-5">
                    {{ config('branding.tagline') }}
                </p>
                
                <div class="p-3 bg-white bg-opacity-10 rounded-3 mb-4 border border-white border-opacity-20 d-inline-block text-start">
                    <div class="d-flex align-items-center gap-2 text-warning fw-bold mb-1">
                        <i class="fas fa-user-shield"></i> संचालक: {{ config('branding.owner', 'Sumit Verma') }}
                    </div>
                    <small class="text-white-50">Choice Study Junction | Telegram: @SssVvv8271</small>
                </div>

                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ url('/tests?category=bihar-let') }}" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-dark rounded-3 shadow">
                        <i class="fas fa-pen-nib me-2"></i> Bihar LET टेस्ट सीरीज़
                    </a>
                    <a href="{{ url('/tests?category=bihar-librarian') }}" class="btn btn-light btn-lg fw-bold px-4 py-3 text-primary rounded-3">
                        <i class="fas fa-book-reader me-2"></i> Bihar Librarian परीक्षा
                    </a>
                </div>
            </div>

            <!-- COMMUNITY & SOCIAL CONNECT -->
            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 shadow-lg text-start">
                    <h5 class="text-white fw-bold mb-3 border-bottom border-white border-opacity-20 pb-2">
                        <i class="fas fa-users text-warning me-2"></i> परीक्षा कम्युनिटी से जुड़ें
                    </h5>
                    
                    <div class="d-flex flex-column gap-3 mb-3">
                        <a href="{{ config('branding.social.telegram') }}" target="_blank" class="btn btn-info text-white fw-bold py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-between px-3">
                            <div>
                                <i class="fab fa-telegram fs-3 me-2 align-middle"></i>
                                <span class="align-middle fs-6">Telegram Group से जुड़ें</span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="{{ config('branding.social.youtube') }}" target="_blank" class="btn btn-danger text-white fw-bold py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-between px-3">
                            <div>
                                <i class="fab fa-youtube fs-3 me-2 align-middle"></i>
                                <span class="align-middle fs-6">Choice Study Junction (YouTube)</span>
                            </div>
                            <i class="fas fa-play-circle"></i>
                        </a>
                    </div>
                    <p class="text-white-50 extra-small mb-0 text-center">
                        <i class="fas fa-check-circle text-success me-1"></i> नवीनतम परीक्षा अपडेट, क्विज़ एवं PDF के लिए तुरंत जुड़ें!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. EXAM TARGET CATEGORIES & QUICK ACCESS -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="text-primary fw-bold text-uppercase small">मुख्य परीक्षा श्रेणियां</span>
            <h2 class="fw-bold text-dark">बिहार लाइब्रेरियन परीक्षा की सम्पूर्ण तैयारी</h2>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 border-start border-5 border-primary bg-light">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary px-3 py-2 fs-6">Bihar LET</span>
                            <i class="fas fa-graduation-cap text-primary fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">बिहार Library Eligibility Test (LET)</h4>
                        <p class="text-muted mb-4">
                            बिहार पात्रता परीक्षा के लिए सिलेबस अनुसार विषयवार क्विज़, प्रैक्टिस सेट्स एवं PDF नोट्स।
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/tests?category=bihar-let') }}" class="btn btn-primary fw-bold flex-fill rounded-3">
                                <i class="fas fa-vial me-1"></i> क्विज़ शुरू करें
                            </a>
                            <a href="{{ url('/materials') }}" class="btn btn-outline-primary fw-bold rounded-3">
                                <i class="fas fa-file-pdf me-1"></i> PDF देखें
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 border-start border-5 border-warning bg-light">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6">Bihar Librarian</span>
                            <i class="fas fa-book-reader text-warning fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">बिहार लाइब्रेरियन भर्ती परीक्षा</h4>
                        <p class="text-muted mb-4">
                            बिहार लाइब्रेरियन पद हेतु मॉक टेस्ट, पिछले वर्षों के प्रश्न-उत्तर तथा विस्तृत वीडियो लेक्चर्स।
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/tests?category=bihar-librarian') }}" class="btn btn-warning text-dark fw-bold flex-fill rounded-3">
                                <i class="fas fa-pen me-1"></i> टेस्ट सीरीज
                            </a>
                            <a href="{{ url('/videos') }}" class="btn btn-outline-warning text-dark fw-bold rounded-3">
                                <i class="fas fa-video me-1"></i> वीडियो देखें
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CATEGORY NAVIGATION BUTTONS -->
        <div class="row g-3 text-center">
            <div class="col-4">
                <a href="{{ url('/tests') }}" class="p-3 bg-light rounded-4 d-block text-decoration-none text-dark shadow-sm border hover-lift">
                    <div class="brand-icon bg-primary text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.4rem;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Quiz (क्विज़)</h6>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ url('/materials') }}" class="p-3 bg-light rounded-4 d-block text-decoration-none text-dark shadow-sm border hover-lift">
                    <div class="brand-icon bg-danger text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.4rem;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h6 class="fw-bold mb-0">PDF (PDF नोट्स)</h6>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ url('/videos') }}" class="p-3 bg-light rounded-4 d-block text-decoration-none text-dark shadow-sm border hover-lift">
                    <div class="brand-icon bg-success text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.4rem;">
                        <i class="fas fa-video"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Video (वीडियो)</h6>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 3. POPULAR TESTS / QUIZZES -->
<section class="py-5 bg-light">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="text-primary fw-bold text-uppercase small">मॉक टेस्ट एवं अभ्यास</span>
                <h2 class="fw-bold text-dark mb-0">नवीनतम Quizzes & Test Series</h2>
            </div>
            <a href="{{ url('/tests') }}" class="btn btn-brand-outline mt-3 mt-md-0 fw-bold">
                सभी क्विज़ देखें <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($popularTests as $quiz)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
                                    {{ $quiz->subject->name ?? 'Bihar Exam' }}
                                </span>
                                @if($quiz->is_paid ?? false)
                                    <span class="badge bg-danger text-white fw-bold px-2 py-1">₹{{ number_format($quiz->price, 2) }}</span>
                                @else
                                    <span class="badge bg-success text-white fw-bold px-2 py-1">निःशुल्क</span>
                                @endif
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($quiz->description, 90) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted small mb-4">
                                <div><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }} मिनट</div>
                                <div><i class="fas fa-question-circle text-info me-1"></i> {{ $quiz->questions_count ?? $quiz->questions()->count() }} प्रश्न</div>
                            </div>

                            <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-primary w-100 fw-bold rounded-3">
                                <i class="fas fa-play me-1"></i> क्विज़ शुरू करें
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted fs-5">क्विज़ लोड हो रहे हैं...</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 4. RECENT STUDY MATERIAL (PDF) -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <span class="text-primary fw-bold text-uppercase small">PDF अध्ययन सामग्री</span>
                <h2 class="fw-bold text-dark mt-2 mb-3">Bihar LET एवं Librarian PDF Notes</h2>
                <p class="text-muted mb-4">
                    अध्यायवार और विषयवार हिंदी/अंग्रेजी नोट्स अपने मोबाइल में आसानी से पढ़ें और डाउनलोड करें।
                </p>
                <a href="{{ route('student.materials') }}" class="btn btn-brand-primary fw-bold">
                    <i class="fas fa-file-pdf me-2"></i> सभी PDF नोट्स देखें
                </a>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    @forelse($recentMaterials as $pdf)
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 shadow-sm border d-flex align-items-start gap-3">
                                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-4">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $pdf->title }}</h6>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-white text-secondary border">{{ $pdf->subject->name ?? 'General' }}</span>
                                        @if($pdf->is_paid ?? false)
                                            <span class="badge bg-danger text-white">₹{{ number_format($pdf->price, 2) }}</span>
                                        @else
                                            <span class="badge bg-success text-white">Free</span>
                                        @endif
                                    </div>
                                    <div class="text-muted extra-small">
                                        <i class="fas fa-download me-1"></i> {{ $pdf->downloads_count }} Downloads
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3 text-muted">PDF सामग्री लोड हो रही है...</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. VIDEO LECTURES SECTION -->
<section class="py-5 bg-light">
    <div class="container py-2">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="text-primary fw-bold text-uppercase small">वीडियो लेक्चर्स</span>
            <h2 class="fw-bold text-dark mb-2">Choice Study Junction - वीडियो क्लासेस</h2>
            <p class="text-muted">YouTube पर महत्वपूर्ण टॉपिक्स की विस्तृत क्लासेस देखें।</p>
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
                <div class="col-12 text-center py-3 text-muted">वीडियो क्लासेस उपलब्ध हैं...</div>
            @endforelse
        </div>
    </div>
</section>

<!-- 6. STATISTICS & PLATFORM INFO -->
<section class="py-5 text-white" style="background-color: #0f172a;">
    <div class="container py-2">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <h2 class="display-6 fw-extrabold mb-1 text-warning">{{ number_format($stats['total_students']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">पंजीकृत छात्र</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-6 fw-extrabold mb-1 text-warning">{{ number_format($stats['total_subjects']) }}</h2>
                <p class="text-white-50 mb-0 font-heading">विषय (Subjects)</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-6 fw-extrabold mb-1 text-warning">{{ number_format($stats['total_materials']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">PDF नोट्स</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-6 fw-extrabold mb-1 text-warning">{{ number_format($stats['total_tests']) }}+</h2>
                <p class="text-white-50 mb-0 font-heading">सक्रिय मॉक टेस्ट</p>
            </div>
        </div>
    </div>
</section>

@endsection
