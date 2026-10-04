@extends('layouts.app')

@section('title', 'Video Lectures | Student Portal')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fab fa-youtube text-danger me-2"></i> Free Video Lectures</h3>
            <p class="text-muted small mb-0">Watch curated Youtube classes directly on the platform.</p>
        </div>

        <form method="GET" action="{{ route('student.videos') }}" class="d-flex gap-2">
            <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- All Subjects --</option>
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-4">
        @forelse($videos as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="{{ $video->title }}" allowfullscreen></iframe>
                    </div>
                    <div class="card-body p-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $video->subject->name ?? 'General' }}</span>
                        <h6 class="fw-bold text-dark mb-1">{{ $video->title }}</h6>
                        <p class="text-muted small mb-0">{{ Str::limit($video->description, 80) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">No video classes available for this subject.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $videos->links() }}
    </div>
</div>
@endsection
