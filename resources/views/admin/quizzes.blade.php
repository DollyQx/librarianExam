@extends('layouts.admin')

@section('title', 'Manage Quizzes & Mock Tests | Admin')
@section('page-title', 'Quizzes & Mock Test Series')

@section('content')
<div class="row g-4">
    <!-- Create Quiz Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Create New Test Series</h5>
            
            @if($errors->has('is_active'))
                <div class="alert alert-warning alert-dismissible fade show small" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('is_active') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.quizzes.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Test Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Bihar Librarian Full Mock Test #1" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subject</label>
                    <select name="subject_id" class="form-select">
                        <option value="">-- All Subjects (Full Mock Test) --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Test Type</label>
                    <select name="type" class="form-select" required>
                        <option value="mock">Full Mock Test</option>
                        <option value="subject">Subject-wise Test</option>
                        <option value="topic">Topic-wise Test</option>
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
                    <label class="form-label fw-bold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="30" min="1" max="300" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Pass Mark (%)</label>
                        <input type="number" name="pass_percentage" class="form-control" value="40" min="0" max="100" step="1" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Marks per MCQ</label>
                        <input type="number" name="marks_per_question" class="form-control" value="1.0" min="0.25" step="0.25" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Negative Marking</label>
                        <input type="number" name="negative_marking_per_question" class="form-control" value="0.25" min="0" step="0.05" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Instructions for students..."></textarea>
                </div>
                <div class="alert alert-info extra-small mb-3">
                    <i class="fas fa-info-circle me-1"></i> New test series are saved as Drafts. You can publish them after adding questions.
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-save me-1"></i> Create Draft Test Series
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
                            <th>Order</th>
                            <th>Test Title</th>
                            <th>Type / Subject</th>
                            <th>Access</th>
                            <th>MCQs</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $quiz->sort_order }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $quiz->title }}</div>
                                    <small class="text-muted">{{ $quiz->duration_minutes }}m | +{{ $quiz->marks_per_question }} marks</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($quiz->type) }}</span>
                                    <div class="small text-muted mt-1">{{ $quiz->subject->name ?? 'All Subjects' }}</div>
                                </td>
                                <td>
                                    @if($quiz->isMembershipRequired())
                                        <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-lock me-1"></i> Membership</span>
                                    @else
                                        <span class="badge bg-success">Free</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info bg-opacity-10 text-info fs-6 fw-bold">{{ $quiz->questions_count }}</span></td>
                                <td>
                                    @if($quiz->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Active</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-dark fw-bold">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.questions', $quiz->id) }}" class="btn btn-sm btn-primary me-1 fw-bold" title="Builder / Edit Questions">
                                        <i class="fas fa-layer-group me-1"></i> MCQs ({{ $quiz->questions_count }})
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editQuizModal{{ $quiz->id }}" title="Edit Settings">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.quizzes.delete', $quiz->id) }}" onsubmit="return confirm('Delete this test series and all its questions?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Test"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editQuizModal{{ $quiz->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Settings: {{ $quiz->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.quizzes.update', $quiz->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Test Title</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Subject</label>
                                                    <select name="subject_id" class="form-select">
                                                        <option value="">-- All Subjects (Full Mock Test) --</option>
                                                        @foreach($subjects as $subj)
                                                            <option value="{{ $subj->id }}" {{ $quiz->subject_id == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Test Type</label>
                                                    <select name="type" class="form-select" required>
                                                        <option value="mock" {{ $quiz->type === 'mock' ? 'selected' : '' }}>Full Mock Test</option>
                                                        <option value="subject" {{ $quiz->type === 'subject' ? 'selected' : '' }}>Subject-wise Test</option>
                                                        <option value="topic" {{ $quiz->type === 'topic' ? 'selected' : '' }}>Topic-wise Test</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Access Control</label>
                                                    <select name="access_type" class="form-select fw-bold">
                                                        <option value="free" {{ $quiz->access_type === 'free' ? 'selected' : '' }}>Free Access</option>
                                                        <option value="membership" {{ $quiz->access_type === 'membership' ? 'selected' : '' }}>Membership Required (₹49 Plan)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $quiz->sort_order }}">
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small">Duration (Mins)</label>
                                                        <input type="number" name="duration_minutes" class="form-control" value="{{ $quiz->duration_minutes }}" min="1" max="300" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small">Pass Mark (%)</label>
                                                        <input type="number" name="pass_percentage" class="form-control" value="{{ $quiz->pass_percentage }}" min="0" max="100" required>
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small">Marks per MCQ</label>
                                                        <input type="number" name="marks_per_question" class="form-control" value="{{ $quiz->marks_per_question }}" step="0.25" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small">Negative Marking</label>
                                                        <input type="number" name="negative_marking_per_question" class="form-control" value="{{ $quiz->negative_marking_per_question }}" step="0.05" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="description" rows="2" class="form-control">{{ $quiz->description }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_quiz_active{{ $quiz->id }}" value="1" {{ $quiz->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold small" for="edit_quiz_active{{ $quiz->id }}">Publish Test (Active)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Quiz</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No tests created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
