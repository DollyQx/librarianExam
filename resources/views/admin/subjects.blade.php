@extends('layouts.admin')

@section('title', 'Manage Subjects | Admin')
@section('page-title', 'Subject Catalog Management')

@section('content')
<div class="row g-4">
    <!-- Add Subject Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add New Subject</h5>
            <form method="POST" action="{{ route('admin.subjects.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subject Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Library Classification" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Icon Class (FontAwesome)</label>
                    <input type="text" name="icon_class" class="form-control" placeholder="e.g. fas fa-sitemap" value="fas fa-book">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0" placeholder="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Short description of subject area..." required></textarea>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold small" for="is_active">Publish Subject (Active)</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-save me-1"></i> Save Subject
                </button>
            </form>
        </div>
    </div>

    <!-- Subjects List Table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-list text-primary me-2"></i> Subject Catalog</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Icon</th>
                            <th>Subject Name</th>
                            <th>Counts</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $subject->sort_order }}</span></td>
                                <td><i class="{{ $subject->icon_class }} fs-4 text-primary"></i></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $subject->name }}</div>
                                    <small class="text-muted extra-small">{{ Str::limit($subject->description, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $subject->topics_count }} Topics</span>
                                    <span class="badge bg-light text-dark border">{{ $subject->quizzes_count }} Quizzes</span>
                                </td>
                                <td>
                                    @if($subject->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}" title="Edit Subject">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.subjects.delete', $subject->id) }}" class="d-inline" onsubmit="return confirm('Delete this subject and all associated topics?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Subject">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Subject: {{ $subject->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Subject Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Icon Class</label>
                                                    <input type="text" name="icon_class" class="form-control" value="{{ $subject->icon_class }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $subject->sort_order }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="description" rows="3" class="form-control" required>{{ $subject->description }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active{{ $subject->id }}" value="1" {{ $subject->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold small" for="edit_is_active{{ $subject->id }}">Active (Published)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Subject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No subjects added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
