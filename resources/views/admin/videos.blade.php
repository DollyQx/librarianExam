@extends('layouts.admin')

@section('title', 'Manage YouTube Videos | Admin')
@section('page-title', 'YouTube Video Lectures')

@section('content')
<div class="row g-4">
    <!-- Add Video Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fab fa-youtube text-danger me-2"></i> Add Video Lecture</h5>
            <form method="POST" action="{{ route('admin.videos.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Video Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Colon Classification (CC) Masterclass" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">YouTube URL</label>
                    <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                    <small class="text-muted">Paste full YouTube watch link or shorts/embed URL.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Lecture overview..."></textarea>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label fw-semibold" for="is_active">Publish Video</label>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold">
                    <i class="fab fa-youtube me-2"></i> Save Video
                </button>
            </form>
        </div>
    </div>

    <!-- Videos List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="fab fa-youtube text-danger me-2"></i> Managed Video Lectures</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title & Subject</th>
                            <th>YouTube ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td style="width: 100px;">
                                    <img src="{{ $video->thumbnail_url }}" alt="Thumb" class="img-fluid rounded border">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $video->title }}</div>
                                    <small class="text-muted d-block">{{ $video->subject->name ?? 'General' }}</small>
                                </td>
                                <td><code>{{ $video->youtube_id }}</code></td>
                                <td>
                                    <span class="badge {{ $video->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $video->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.videos.delete', $video->id) }}" onsubmit="return confirm('Delete video?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No video lectures added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
