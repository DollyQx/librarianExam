@extends('layouts.admin')

@section('title', 'Manage Topics | Admin')
@section('page-title', 'Topics Management')

@section('content')
<div class="row g-4">
    <!-- Add Topic Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add New Topic</h5>
            <form method="POST" action="{{ route('admin.topics.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Topic Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Dewey Decimal Classification" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Brief topic description..."></textarea>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold small" for="is_active">Publish Topic (Active)</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-save me-1"></i> Save Topic
                </button>
            </form>
        </div>
    </div>

    <!-- Topics List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-layer-group text-primary me-2"></i> All Topics</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Topic Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $topic->sort_order }}</span></td>
                                <td class="fw-bold text-dark">{{ $topic->name }}</td>
                                <td><span class="badge bg-light text-primary border">{{ $topic->subject->name ?? 'N/A' }}</span></td>
                                <td>
                                    <span class="badge {{ $topic->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }} fw-bold">
                                        {{ $topic->is_active ? 'Active' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editTopicModal{{ $topic->id }}" title="Edit Topic">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.topics.delete', $topic->id) }}" onsubmit="return confirm('Are you sure you want to delete this topic?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Topic"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editTopicModal{{ $topic->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Topic: {{ $topic->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.topics.update', $topic->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Subject</label>
                                                    <select name="subject_id" class="form-select" required>
                                                        @foreach($subjects as $subj)
                                                            <option value="{{ $subj->id }}" {{ $topic->subject_id == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Topic Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $topic->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $topic->sort_order }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="description" rows="3" class="form-control">{{ $topic->description }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_topic_active{{ $topic->id }}" value="1" {{ $topic->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold small" for="edit_topic_active{{ $topic->id }}">Active (Published)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Topic</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No topics created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
