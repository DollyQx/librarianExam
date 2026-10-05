@extends('layouts.app')

@section('title', 'Terms & Conditions | ' . config('branding.name'))

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <h1 class="fw-bold text-dark mb-4">Terms & Conditions</h1>
        <p class="text-muted">Last Updated: {{ date('F Y') }}</p>

        <h5 class="fw-bold text-dark mt-4">1. Acceptance of Terms</h5>
        <p class="text-muted">By registering or accessing {{ config('branding.name') }}, you agree to comply with these terms. The platform is designed for Bihar LET and Bihar Librarian exam preparation.</p>

        <h5 class="fw-bold text-dark mt-4">2. Educational Content</h5>
        <p class="text-muted">Study materials, practice tests, and video resources provided on this portal are for individual candidate preparation. Commercial redistribution or automated scraping is prohibited.</p>

        <h5 class="fw-bold text-dark mt-4">3. User Conduct</h5>
        <p class="text-muted">Students are expected to use the Doubts feature respectfully for academic queries. Misconduct or spam will result in account suspension.</p>

        <h5 class="fw-bold text-dark mt-4">4. Disclaimer</h5>
        <p class="text-muted">While every effort is made to ensure accurate answer keys and explanations, candidates are advised to cross-reference official syllabus guidelines and notifications.</p>
    </div>
</div>
@endsection
