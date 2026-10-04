@extends('layouts.app')

@section('title', 'My Account Profile | Student Portal')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h4 class="fw-bold text-dark mb-4"><i class="fas fa-user-circle text-primary me-2"></i> Account Profile Settings</h4>

                <form method="POST" action="{{ route('student.profile.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        <small class="text-muted">Email address cannot be changed.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mobile Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="10-digit mobile number">
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold text-dark mb-3">Change Password <small class="text-muted">(Leave blank to keep unchanged)</small></h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3">
                        <i class="fas fa-save me-2"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
