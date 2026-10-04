@extends('layouts.admin')

@section('title', 'Manage Quizzes & Mock Tests | Admin')
@section('page-title', 'Quizzes & Mock Test Series')

@section('content')
<div class="row g-4">
    <!-- Create Quiz Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Create New Test Series</h5>
            <form method="POST" action="{{ route('admin.quizzes.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Test Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. KVS Librarian Full Mock Test #1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">-- All Subjects (Full Mock Test) --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Test Type</label>
                    <select name="type" class="form-select" required>
                        <option value="mock">Full Mock Test</option>
                        <option value="subject">Subject-wise Test</option>
                        <option value="topic">Topic-wise Test</option>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="30" min="1" max="300" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Pass Mark (%)</label>
                        <input type="number" name="pass_percentage" class="form-control" value="40" min="0" max="100" step="1" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Marks per MCQ</label>
                        <input type="number" name="marks_per_question" class="form-control" value="1.0" min="0.25" step="0.25" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Negative Marking</label>
                        <input type="number" name="negative_marking_per_question" class="form-control" value="0.25" min="0" step="0.05" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Instructions for students..."></textarea>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label fw-semibold" for="is_active">Publish Test</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-save me-2"></i> Create Test Series
                </button>
            </form>
        </div>
    </div>

    <!-- Quizzes List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-vial text-primary me-2"></i> Created Quizzes & Tests</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Test Title</th>
                            <th>Type / Subject</th>
                            <th>Duration</th>
                            <th>MCQs</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $quiz->title }}</div>
                                    <small class="text-muted">+{{ $quiz->marks_per_question }} marks / -{{ $quiz->negative_marking_per_question }} negative</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($quiz->type) }}</span>
                                    <div class="small text-muted mt-1">{{ $quiz->subject->name ?? 'All Subjects' }}</div>
                                </td>
                                <td><i class="fas fa-clock text-warning me-1"></i> {{ $quiz->duration_minutes }}m</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info fs-6 fw-bold">{{ $quiz->questions_count }}</span></td>
                                <td>
                                    <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $quiz->is_active ? 'Active' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.questions', $quiz->id) }}" class="btn btn-sm btn-outline-primary me-1 fw-bold">
                                        <i class="fas fa-edit me-1"></i> Questions ({{ $quiz->questions_count }})
                                    </a>
                                    <form method="POST" action="{{ route('admin.quizzes.delete', $quiz->id) }}" onsubmit="return confirm('Delete this test series?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No tests created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
