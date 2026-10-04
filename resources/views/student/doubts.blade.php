@extends('layouts.app')

@section('title', 'Student Doubts & Mentor Q&A | Librarian Prep')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Submit Doubt Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-question-circle text-warning me-2"></i> Ask a Doubt</h5>
                <form method="POST" action="{{ route('student.doubts.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Doubt Title / Question</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Difference between AACR2 and CCC?" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Subject <small class="text-muted">(Optional)</small></label>
                        <select name="subject_id" class="form-select">
                            <option value="">-- General / Any Subject --</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detailed Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Explain your doubt in detail..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                        <i class="fas fa-paper-plane me-2"></i> Submit Doubt Free
                    </button>
                </form>
            </div>
        </div>

        <!-- Doubts List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-comments text-primary me-2"></i> My Submitted Doubts & Replies</h5>

                @forelse($doubts as $doubt)
                    <div class="card mb-4 border rounded-3 overflow-hidden">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $doubt->title }}</h6>
                                <small class="text-muted">Submitted {{ $doubt->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge {{ $doubt->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                {{ ucfirst($doubt->status) }}
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-dark small mb-3">{{ $doubt->description }}</p>

                            @if($doubt->replies->count() > 0)
                                <div class="p-3 bg-success bg-opacity-10 border-start border-4 border-success rounded-3">
                                    <h6 class="fw-bold text-success mb-2"><i class="fas fa-user-shield me-1"></i> Teacher Reply:</h6>
                                    @foreach($doubt->replies as $reply)
                                        <p class="text-dark small mb-1">{{ $reply->reply_text }}</p>
                                        <small class="text-muted extra-small">Replied on {{ $reply->created_at->format('M d, Y h:i A') }}</small>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-2 bg-light text-muted small rounded text-center">
                                    <i class="fas fa-clock text-warning me-1"></i> Pending teacher review. Check back soon for a reply!
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">You haven't submitted any doubts yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
