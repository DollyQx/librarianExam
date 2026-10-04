@extends('layouts.app')

@section('title', 'Student & Admin Login | Librarian Exam Prep')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="brand-icon mx-auto mb-2 bg-white text-primary" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Welcome Back</h4>
                    <p class="mb-0 text-white-50 small">Log in to access your free study materials & mock tests</p>
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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-muted small" for="remember">Remember me</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand-primary w-100 py-3 text-uppercase tracking-wider fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i> Log In
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted small">
                        Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register for Free</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
