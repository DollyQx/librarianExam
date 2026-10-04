@extends('layouts.app')

@section('title', 'Free Student Registration | Librarian Exam Prep')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="brand-icon mx-auto mb-2 bg-white text-primary" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Create Free Student Account</h4>
                    <p class="mb-0 text-white-50 small">Start preparing for Librarian competitive exams today</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom mb-4">
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Mobile Number <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="10-digit mobile number" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-check-double"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand-primary w-100 py-3 text-uppercase tracking-wider fw-bold">
                            <i class="fas fa-user-check me-2"></i> Register Now (100% Free)
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted small">
                        Already registered? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Log In Here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
