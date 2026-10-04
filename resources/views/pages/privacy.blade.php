@extends('layouts.app')

@section('title', 'Privacy Policy | ' . config('app.name', 'Librarian Exam Prep'))

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <h1 class="fw-bold text-dark mb-4">Privacy Policy</h1>
        <p class="text-muted">Last Updated: {{ date('F Y') }}</p>

        <h5 class="fw-bold text-dark mt-4">1. Information We Collect</h5>
        <p class="text-muted">We collect information you provide directly to us when creating a free account, such as your name, email address, and optional mobile number. We also record your test attempt scores to display your personal academic history.</p>

        <h5 class="fw-bold text-dark mt-4">2. How We Use Information</h5>
        <p class="text-muted">Your information is used solely to maintain your student account, track your exam preparation progress, allow you to submit doubts, and provide teacher responses. We never sell your personal data.</p>

        <h5 class="fw-bold text-dark mt-4">3. Log Files & Cookies</h5>
        <p class="text-muted">Like standard web applications, our platform logs basic access information and uses essential session cookies to keep you securely logged into your student dashboard.</p>

        <h5 class="fw-bold text-dark mt-4">4. AdSense & Third-Party Compliance</h5>
        <p class="text-muted">In the future, third-party vendor advertisements (such as Google AdSense) may serve ads on this website using cookies to display advertisements based on prior visits. All educational services remain 100% free.</p>
    </div>
</div>
@endsection
