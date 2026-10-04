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
                    <label class="form-label fw-bold">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Topic Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Dewey Decimal Classification" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Brief topic description..."></textarea>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label fw-semibold" for="is_active">Active Status</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-save me-2"></i> Save Topic
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
                            <th>#</th>
                            <th>Topic Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topics as $topic)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold text-dark">{{ $topic->name }}</td>
                                <td><span class="badge bg-light text-primary border">{{ $topic->subject->name ?? 'N/A' }}</span></td>
                                <td>
                                    <span class="badge {{ $topic->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $topic->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.topics.delete', $topic->id) }}" onsubmit="return confirm('Are you sure?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
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
