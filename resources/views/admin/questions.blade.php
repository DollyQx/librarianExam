@extends('layouts.admin')

@section('title', 'Manage Questions | Admin')
@section('page-title', 'Question Builder: ' . $quiz->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.quizzes') }}" class="btn btn-outline-secondary btn-sm fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Back to All Quizzes
    </a>
</div>

<div class="row g-4">
    <!-- Add Question Form -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-primary me-2"></i> Add New MCQ Question</h5>
            <form method="POST" action="{{ route('admin.questions.store', $quiz->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Question Statement</label>
                    <textarea name="question_text" rows="3" class="form-control" placeholder="Enter question text here..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Options (Select radio for Correct Option)</label>
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
                    <label class="form-label fw-bold">Answer Explanation / Hint</label>
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
                <div class="p-3 mb-3 border rounded-3 bg-light">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold text-dark mb-0">Q{{ $index + 1 }}. {{ $question->question_text }}</h6>
                        <form method="POST" action="{{ route('admin.questions.delete', $question->id) }}" onsubmit="return confirm('Delete question?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fas fa-times"></i></button>
                        </form>
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
            @empty
                <div class="text-center text-muted py-4">No questions added to this test series yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
