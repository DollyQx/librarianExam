@extends('layouts.admin')

@section('title', 'Manage Questions | Admin')
@section('page-title', 'Question Builder: ' . $quiz->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.quizzes') }}" class="btn btn-outline-secondary btn-sm fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Back to All Quizzes
    </a>
    <div>
        @if($quiz->is_active)
            <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> Published & Live</span>
        @else
            <span class="badge bg-warning text-dark fs-6"><i class="fas fa-edit me-1"></i> Draft (Inactive)</span>
            <form method="POST" action="{{ route('admin.quizzes.update', $quiz->id) }}" class="d-inline ms-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $quiz->title }}">
                <input type="hidden" name="type" value="{{ $quiz->type }}">
                <input type="hidden" name="duration_minutes" value="{{ $quiz->duration_minutes }}">
                <input type="hidden" name="pass_percentage" value="{{ $quiz->pass_percentage }}">
                <input type="hidden" name="marks_per_question" value="{{ $quiz->marks_per_question }}">
                <input type="hidden" name="negative_marking_per_question" value="{{ $quiz->negative_marking_per_question }}">
                <input type="hidden" name="is_active" value="1">
                <button type="submit" class="btn btn-sm btn-success fw-bold" {{ !$quiz->hasValidQuestions() ? 'disabled' : '' }}>
                    <i class="fas fa-rocket me-1"></i> Publish Test Now
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Add Question Form -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add New MCQ Question</h5>
            <form method="POST" action="{{ route('admin.questions.store', $quiz->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Question Statement</label>
                    <textarea name="question_text" rows="3" class="form-control" placeholder="Enter question text here..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Options (Select radio for Correct Option)</label>
                    <div class="d-flex flex-column gap-2">
                        @for($i = 0; $i < 4; $i++)
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} title="Mark as Correct Option">
                                </div>
                                <span class="input-group-text fw-bold">{{ chr(65 + $i) }}</span>
                                <input type="text" name="options[]" class="form-control" placeholder="Option {{ chr(65 + $i) }} text" required>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">Answer Explanation / Hint</label>
                    <textarea name="explanation" rows="3" class="form-control" placeholder="Detailed explanation showing why the correct answer is right..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-plus me-2"></i> Save Question & Options
                </button>
            </form>
        </div>
    </div>

    <!-- Questions List -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4">
                <i class="fas fa-list-ol text-primary me-2"></i> Questions in this Test ({{ $quiz->questions->count() }})
            </h5>
            
            @forelse($quiz->questions as $index => $question)
                <div class="p-3 mb-3 border rounded-3 bg-light position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold text-dark mb-0 pe-4">Q{{ $question->order }}. {{ $question->question_text }}</h6>
                        <div>
                            <button class="btn btn-sm btn-outline-primary py-0 px-2 me-1" data-bs-toggle="modal" data-bs-target="#editQuestionModal{{ $question->id }}" title="Edit Question">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.questions.delete', $question->id) }}" onsubmit="return confirm('Delete question?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete Question"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        @foreach($question->options as $optIndex => $option)
                            <div class="col-6">
                                <div class="p-2 rounded-2 border {{ $option->is_correct ? 'bg-success bg-opacity-10 border-success fw-bold text-success' : 'bg-white text-muted' }} extra-small">
                                    <span class="me-1">{{ chr(65 + $optIndex) }})</span> {{ $option->option_text }}
                                    @if($option->is_correct) <i class="fas fa-check-circle ms-1"></i> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($question->explanation)
                        <div class="p-2 rounded-2 bg-warning bg-opacity-10 text-dark extra-small">
                            <strong><i class="fas fa-info-circle me-1 text-warning"></i> Explanation:</strong> {{ $question->explanation }}
                        </div>
                    @endif
                </div>

                <!-- Edit Question Modal -->
                <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content rounded-4 border-0">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Question Q{{ $question->order }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.questions.update', $question->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Question Order</label>
                                        <input type="number" name="order" class="form-control" value="{{ $question->order }}" min="1">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Question Statement</label>
                                        <textarea name="question_text" rows="3" class="form-control" required>{{ $question->question_text }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Options (Select radio for Correct Option)</label>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($question->options as $optIdx => $option)
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $optIdx }}" {{ $option->is_correct ? 'checked' : '' }}>
                                                    </div>
                                                    <span class="input-group-text fw-bold">{{ chr(65 + $optIdx) }}</span>
                                                    <input type="text" name="options[]" class="form-control" value="{{ $option->option_text }}" required>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Answer Explanation</label>
                                        <textarea name="explanation" rows="3" class="form-control">{{ $question->explanation }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary fw-bold">Update Question</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No questions added to this test series yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
