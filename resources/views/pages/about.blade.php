@extends('layouts.app')

@section('title', 'About Us | ' . config('app.name', 'Librarian Exam Prep'))

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">Our Mission</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Empowering Every Librarian Aspirant in India</h1>
            <p class="lead text-muted mb-4">
                We believe high-quality competitive examination preparation should be freely accessible to every student, regardless of their financial background.
            </p>
            <p class="text-muted">
                {{ config('app.name', 'Librarian Exam Prep') }} was built as a dedicated learning environment for candidates preparing for Library & Information Science examinations, including KVS, NVS, EMRS, UGC-NET, and State Public Service Commission Librarian posts.
            </p>
        </div>
        <div class="col-lg-6 text-center">
            <div class="p-4 rounded-4 shadow-sm bg-white border">
                <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80" alt="About Platform" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-primary text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h5 class="fw-bold text-dark">Free Study Notes</h5>
                <p class="text-muted small mb-0">High-yield revision notes covering library classification, cataloguing rules, library management, and computer fundamentals.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-warning text-dark mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-vial"></i>
                </div>
                <h5 class="fw-bold text-dark">Real-Time Mock Tests</h5>
                <p class="text-muted small mb-0">Timed online examination engine simulating actual competitive exam interfaces with instant evaluation and detailed explanations.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                <div class="brand-icon bg-success text-white mx-auto mb-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-comments"></i>
                </div>
                <h5 class="fw-bold text-dark">Doubt Resolution</h5>
                <p class="text-muted small mb-0">Direct teacher assistance allowing students to ask doubts regarding tricky questions and receive prompt replies.</p>
            </div>
        </div>
    </div>
</div>
@endsection
