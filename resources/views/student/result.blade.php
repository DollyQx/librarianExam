@extends('layouts.app')

@section('title', 'Test Result & Scorecard | Librarian Prep')

@section('content')
<div class="container py-4">
    <!-- Scorecard Summary Banner -->
    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 mb-5 bg-white text-center position-relative overflow-hidden">
        <div class="max-w-700 mx-auto">
            @if($attempt->percentage >= ($quiz->pass_percentage ?? 40))
                <div class="brand-icon bg-success text-white mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 class="fw-bold text-success mb-1">Congratulations! Test Passed</h2>
                <p class="text-muted mb-4">You have successfully cleared the passing criteria for <strong>{{ $quiz->title }}</strong>.</p>
            @else
                <div class="brand-icon bg-danger text-white mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="fw-bold text-danger mb-1">Keep Practicing!</h2>
                <p class="text-muted mb-4">You scored below the passing mark of {{ $quiz->pass_percentage }}%. Review the detailed explanations below and re-attempt.</p>
            @endif

            <div class="row g-3 text-center my-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted extra-small text-uppercase font-heading fw-bold">Score Obtained</div>
                        <h3 class="fw-bold text-primary mb-0">{{ $attempt->marks_obtained }} / {{ $attempt->max_marks }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted extra-small text-uppercase font-heading fw-bold">Percentage</div>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($attempt->percentage, 1) }}%</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted extra-small text-uppercase font-heading fw-bold">Accuracy</div>
                        <h3 class="fw-bold text-success mb-0">
                            {{ $attempt->attempted_questions > 0 ? number_format(($attempt->correct_answers / $attempt->attempted_questions) * 100, 1) : 0 }}%
                        </h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted extra-small text-uppercase font-heading fw-bold">Time Taken</div>
                        <h3 class="fw-bold text-warning mb-0">{{ $attempt->formatted_time_taken }}</h3>
                    </div>
                </div>
            </div>

            <!-- Performance Breakdown Pills -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-check-circle me-1"></i> {{ $attempt->correct_answers }} Correct (+{{ $attempt->correct_answers * $quiz->marks_per_question }} Marks)
                </span>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-times-circle me-1"></i> {{ $attempt->wrong_answers }} Wrong (-{{ number_format($attempt->wrong_answers * $quiz->negative_marking_per_question, 2) }} Deducted)
                </span>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-minus-circle me-1"></i> {{ $attempt->unattempted_questions }} Unattempted
                </span>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('student.tests.show', $quiz->id) }}" class="btn btn-brand-primary">
                    <i class="fas fa-redo me-1"></i> Re-attempt Test
                </a>
                <a href="{{ route('student.dashboard') }}" class="btn btn-brand-outline">
                    <i class="fas fa-home me-1"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Question-wise Detailed Answer Key & Explanations -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <h4 class="fw-bold text-dark mb-4"><i class="fas fa-list-check text-primary me-2"></i> Question-by-Question Solution & Explanations</h4>

        @foreach($attempt->answers as $index => $answer)
            @php
                $q = $answer->question;
            @endphp
            <div class="p-4 mb-4 border rounded-4 {{ $answer->is_correct ? 'border-success bg-success bg-opacity-10' : ($answer->selected_option_id ? 'border-danger bg-danger bg-opacity-10' : 'border-secondary bg-light') }}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge {{ $answer->is_correct ? 'bg-success' : ($answer->selected_option_id ? 'bg-danger' : 'bg-secondary') }} px-3 py-2 rounded-pill fw-bold">
                        Question {{ $index + 1 }} • {{ $answer->is_correct ? 'Correct' : ($answer->selected_option_id ? 'Wrong Answer' : 'Unattempted') }}
                    </span>
                    <span class="fw-bold small">{{ $answer->marks_awarded > 0 ? '+'.$answer->marks_awarded : $answer->marks_awarded }} Marks</span>
                </div>

                <h5 class="fw-bold text-dark mb-4">{{ $q->question_text }}</h5>

                <div class="row g-3 mb-4">
                    @foreach($q->options as $optIndex => $opt)
                        @php
                            $isSelected = $answer->selected_option_id == $opt->id;
                            $isCorrect = $opt->is_correct;
                        @endphp
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between 
                                {{ $isCorrect ? 'bg-success text-white fw-bold border-success' : ($isSelected ? 'bg-danger text-white fw-bold border-danger' : 'bg-white text-dark') }}">
                                <div>
                                    <span class="me-2">{{ chr(65 + $optIndex) }})</span> {{ $opt->option_text }}
                                </div>
                                @if($isCorrect)
                                    <i class="fas fa-check-circle fs-5"></i>
                                @elseif($isSelected)
                                    <i class="fas fa-times-circle fs-5"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($q->explanation)
                    <div class="p-3 rounded-3 bg-white border border-warning">
                        <h6 class="fw-bold text-warning mb-1"><i class="fas fa-lightbulb me-1"></i> Solution Explanation:</h6>
                        <p class="text-dark small mb-0">{{ $q->explanation }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
