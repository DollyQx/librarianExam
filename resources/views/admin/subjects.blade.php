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
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Short description of subject area..." required></textarea>
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
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-list text-primary me-2"></i> Active Subjects</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Icon</th>
                            <th>Subject Name</th>
                            <th>Topics</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><i class="{{ $subject->icon_class }} fs-4 text-primary"></i></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $subject->name }}</div>
                                    <small class="text-muted extra-small">{{ Str::limit($subject->description, 50) }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $subject->topics_count }} Topics</span></td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.subjects.delete', $subject->id) }}" class="d-inline" onsubmit="return confirm('Delete this subject and all associated topics?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Subject">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
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
