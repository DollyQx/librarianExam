@extends('layouts.app')

@section('title', 'About Us | ' . config('branding.name', 'BIHAR LET/Librarian Exam'))

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">हमारे बारे में</span>
            <h1 class="display-6 fw-bold text-dark mb-3">BIHAR LET एवं Bihar Librarian परीक्षा पोर्टल</h1>
            <p class="lead text-muted mb-4">
                यह पोर्टल विशेष रूप से बिहार LET (Library Eligibility Test) तथा बिहार लाइब्रेरियन प्रतियोगिता परीक्षाओं की तैयारी करने वाले अभ्यर्थियों के लिए समर्पित है।
            </p>
            <p class="text-muted">
                <strong>संचालक: Sumit Verma (Choice Study Junction)</strong><br>
                हमारा मुख्य उद्देश्य बिहार के ग्रामीण एवं शहरी क्षेत्रों के छात्रों को गुणवत्तापूर्ण क्विज़, PDF नोट्स एवं वीडियो लेक्चर्स सहजता से उपलब्ध कराना है।
            </p>
            <div class="d-flex gap-3 mt-4">
                <a href="{{ config('branding.social.telegram') }}" target="_blank" class="btn btn-info text-white fw-bold px-3 py-2 rounded-3">
                    <i class="fab fa-telegram me-1"></i> Telegram से जुड़ें
                </a>
                <a href="{{ config('branding.social.youtube') }}" target="_blank" class="btn btn-danger text-white fw-bold px-3 py-2 rounded-3">
                    <i class="fab fa-youtube me-1"></i> YouTube Channel
                </a>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="p-4 rounded-4 shadow-sm bg-white border">
                <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80" alt="About BIHAR LET Portal" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-primary text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h5 class="fw-bold text-dark">PDF नोट्स</h5>
                <p class="text-muted small mb-0">विषयवार एवं अध्यायवार उच्च गुणवत्ता वाले स्टडी नोट्स।</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-warning text-dark mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-vial"></i>
                </div>
                <h5 class="fw-bold text-dark">ऑनलाइन क्विज़</h5>
                <p class="text-muted small mb-0">वास्तविक परीक्षा पैटर्न पर आधारित टाइमर के साथ प्रैक्टिस क्विज़।</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-success text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fab fa-youtube"></i>
                </div>
                <h5 class="fw-bold text-dark">Choice Study Junction</h5>
                <p class="text-muted small mb-0">सुमित वर्मा सर द्वारा यूट्यूब पर मुफ्त ऑनलाइन वीडियो लेक्चर्स।</p>
            </div>
        </div>
    </div>
</div>
@endsection
