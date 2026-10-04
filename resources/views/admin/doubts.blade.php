@extends('layouts.admin')

@section('title', 'Manage Student Doubts | Admin')
@section('page-title', 'Student Doubts & Mentorship Replies')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-question-circle text-warning me-2"></i> Student Doubts Queue</h5>
    
    @forelse($doubts as $doubt)
        <div class="card mb-4 border rounded-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0">{{ $doubt->title }}</h6>
                    <small class="text-muted">Submitted by <strong>{{ $doubt->user->name ?? 'Student' }}</strong> ({{ $doubt->user->email ?? '' }}) • {{ $doubt->created_at->diffForHumans() }}</small>
                </div>
                <span class="badge {{ $doubt->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                    {{ ucfirst($doubt->status) }}
                </span>
            </div>
            <div class="card-body p-3">
                <p class="text-dark mb-3">{{ $doubt->description }}</p>

                <!-- Existing Replies -->
                @if($doubt->replies->count() > 0)
                    <div class="p-3 bg-light rounded-3 mb-3 border-start border-4 border-success">
                        <h6 class="fw-bold text-success mb-2"><i class="fas fa-reply me-1"></i> Teacher / Admin Reply:</h6>
                        @foreach($doubt->replies as $reply)
                            <p class="text-dark small mb-1">{{ $reply->reply_text }}</p>
                            <small class="text-muted extra-small">By {{ $reply->user->name ?? 'Admin' }} on {{ $reply->created_at->format('M d, Y h:i A') }}</small>
                        @endforeach
                    </div>
                @endif

                <!-- Reply Form -->
                <form method="POST" action="{{ route('admin.doubts.reply', $doubt->id) }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="reply_text" class="form-control" placeholder="Write teacher reply..." required>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="fas fa-paper-plane me-1"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">No student doubts submitted yet.</div>
    @endforelse

    <div class="mt-3">
        {{ $doubts->links() }}
    </div>
</div>
@endsection
