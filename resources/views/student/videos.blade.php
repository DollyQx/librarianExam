@extends('layouts.app')

@section('title', 'वीडियो लेक्चर्स | ' . config('branding.name'))

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fab fa-youtube text-danger me-2"></i> वीडियो लेक्चर्स - Choice Study Junction</h3>
            <p class="text-muted small mb-0">सुमित वर्मा सर द्वारा तैयार बिहार LET एवं लाइब्रेरियन वीडियो क्लासेस।</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ config('branding.social.youtube') }}" target="_blank" class="btn btn-danger btn-sm fw-bold">
                <i class="fab fa-youtube me-1"></i> Subscribe YouTube
            </a>
            <form method="GET" action="{{ route('student.videos') }}" class="d-flex">
                <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- सभी विषय --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
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
            <div class="col-12 text-center text-muted py-5 fs-5">कोई वीडियो क्लास उपलब्ध नहीं है।</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $videos->links() }}
    </div>
</div>
@endsection
