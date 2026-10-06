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
                    <label class="form-label fw-bold small">Video Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Colon Classification (CC) Masterclass" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Access Control</label>
                    <select name="access_type" class="form-select fw-bold">
                        <option value="free">Free Access</option>
                        <option value="membership">Membership Required (₹49 Plan)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">YouTube URL</label>
                    <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                    <small class="text-muted extra-small">Paste YouTube watch link or shorts/embed URL.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Custom Thumbnail URL <small class="text-muted">(Optional)</small></label>
                    <input type="url" name="thumbnail_url" class="form-control" placeholder="https://... (defaults to YouTube HQ)">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Lecture overview..."></textarea>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold small" for="is_active">Publish Video (Active)</label>
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
                            <th>Order</th>
                            <th>Thumbnail</th>
                            <th>Title & Subject</th>
                            <th>Access</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $video->sort_order }}</span></td>
                                <td style="width: 90px;">
                                    <img src="{{ $video->thumbnail_url }}" alt="Thumb" class="img-fluid rounded border shadow-sm">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $video->title }}</div>
                                    <small class="text-muted d-block">{{ $video->subject->name ?? 'General' }}</small>
                                </td>
                                <td>
                                    @if($video->isMembershipRequired())
                                        <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-lock me-1"></i> Membership</span>
                                    @else
                                        <span class="badge bg-success">Free</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $video->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }} fw-bold">
                                        {{ $video->is_active ? 'Active' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editVideoModal{{ $video->id }}" title="Edit Video"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('admin.videos.delete', $video->id) }}" onsubmit="return confirm('Delete video?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Video"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editVideoModal{{ $video->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Video Lecture</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.videos.update', $video->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Video Title</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Subject</label>
                                                    <select name="subject_id" class="form-select" required>
                                                        @foreach($subjects as $subj)
                                                            <option value="{{ $subj->id }}" {{ $video->subject_id == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Access Control</label>
                                                    <select name="access_type" class="form-select fw-bold">
                                                        <option value="free" {{ $video->access_type === 'free' ? 'selected' : '' }}>Free Access</option>
                                                        <option value="membership" {{ $video->access_type === 'membership' ? 'selected' : '' }}>Membership Required (₹49 Plan)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">YouTube URL</label>
                                                    <input type="url" name="youtube_url" class="form-control" value="{{ $video->youtube_url }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Custom Thumbnail URL</label>
                                                    <input type="url" name="thumbnail_url" class="form-control" value="{{ $video->thumbnail_url }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $video->sort_order }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="description" rows="2" class="form-control">{{ $video->description }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_video_active{{ $video->id }}" value="1" {{ $video->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold small" for="edit_video_active{{ $video->id }}">Active (Published)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Video</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No video lectures added yet.</td></tr>
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
