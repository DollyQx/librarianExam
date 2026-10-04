@extends('layouts.app')

@section('title', 'Contact Us | Librarian Exam Prep')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="fw-bold mb-1">Contact Support & Mentors</h3>
                    <p class="mb-0 text-white-50 small">Have a query or feedback? Send us a message below.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-custom mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Your Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Your Email</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g. Suggestion or Question about Mock Test" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Message</label>
                                <textarea name="message" rows="5" class="form-control" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-brand-primary w-100 py-3 fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
