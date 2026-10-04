@extends('layouts.app')

@section('title', 'Taking Test: ' . $quiz->title)

@section('styles')
<style>
    .test-header {
        background-color: #0f172a;
        color: white;
        padding: 12px 20px;
        position: sticky;
        top: 0;
        z-index: 1040;
    }
    .question-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 24px;
    }
    .option-label {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 12px;
        font-weight: 500;
        word-break: break-word;
    }
    .option-label:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .option-input:checked + .option-label {
        border-color: #2563eb;
        background-color: #ebf5ff;
        color: #1e40af;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
    }
    .palette-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
        color: #475569;
        transition: all 0.2s;
    }
    .palette-btn.answered {
        background-color: #10b981 !important;
        color: white !important;
        border-color: #059669 !important;
    }
    .palette-btn.unanswered {
        background-color: #ef4444 !important;
        color: white !important;
        border-color: #dc2626 !important;
    }
    .palette-btn.current {
        box-shadow: 0 0 0 3px #2563eb;
        border-color: #2563eb !important;
        font-weight: 800;
    }

    @media (max-width: 991.98px) {
        .test-header {
            padding: 10px 12px;
        }
        .question-card {
            padding: 16px;
        }
        .palette-sidebar {
            margin-top: 20px;
        }
    }
</style>
@endsection

@section('content')
<!-- Test Top Header -->
<div class="test-header shadow-sm mb-4">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="fw-bold text-white mb-0 text-truncate" style="max-width: 320px;">{{ $quiz->title }}</h5>
            <small class="text-white-50">Total MCQs: {{ $quiz->questions->count() }} | Pass: {{ $quiz->pass_percentage }}%</small>
        </div>

        <div class="d-flex align-items-center gap-3 ms-auto">
            <div class="bg-warning text-dark px-3 py-1 py-md-2 rounded-3 fw-bold d-flex align-items-center gap-2">
                <i class="fas fa-clock fs-5"></i>
                <div>
                    <div style="font-size: 0.6rem; line-height: 1;">TIME REMAINING</div>
                    <span id="timerDisplay" style="font-size: 1.1rem; font-family: monospace;">00:00</span>
                </div>
            </div>

            <button type="button" class="btn btn-danger fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#submitModal">
                <i class="fas fa-check-circle me-1"></i> Submit
            </button>
        </div>
    </div>
</div>

<div class="container pb-5">
    <form id="testForm" method="POST" action="{{ route('student.tests.submit', $quiz->id) }}" onsubmit="disableSubmitButtons()">
        @csrf
        <input type="hidden" name="time_taken_seconds" id="timeTakenSeconds" value="0">

        <div class="row g-4">
            <!-- Question Display Section -->
            <div class="col-lg-8">
                @foreach($quiz->questions as $index => $question)
                    <div class="question-card question-block" id="qBlock_{{ $index }}" style="{{ $index > 0 ? 'display: none;' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold">Question {{ $index + 1 }} of {{ $quiz->questions->count() }}</span>
                            <span class="text-muted small"><i class="fas fa-award text-warning me-1"></i> +{{ $question->marks }} / -{{ $quiz->negative_marking_per_question }}</span>
                        </div>

                        <h5 class="fw-bold text-dark mb-4" style="line-height: 1.5;">{{ $question->question_text }}</h5>

                        <div class="options-group mb-4">
                            @foreach($question->options as $optIndex => $option)
                                <div>
                                    <input type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           id="opt_{{ $question->id }}_{{ $option->id }}" 
                                           value="{{ $option->id }}" 
                                           class="option-input d-none" 
                                           onchange="onOptionSelect({{ $index }})">
                                    <label for="opt_{{ $question->id }}_{{ $option->id }}" class="option-label">
                                        <span class="badge bg-light text-dark border me-3 px-3 py-2 fw-bold" style="font-size: 0.9rem;">
                                            {{ chr(65 + $optIndex) }}
                                        </span>
                                        <span>{{ $option->option_text }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary btn-sm fw-bold" onclick="clearSelection({{ $index }}, {{ $question->id }})">
                                <i class="fas fa-eraser me-1"></i> Clear Response
                            </button>

                            <div class="d-flex gap-2 ms-auto">
                                @if($index > 0)
                                    <button type="button" class="btn btn-outline-primary fw-bold" onclick="showQuestion({{ $index - 1 }})">
                                        <i class="fas fa-chevron-left me-1"></i> Previous
                                    </button>
                                @endif
                                @if($index < $quiz->questions->count() - 1)
                                    <button type="button" class="btn btn-primary fw-bold px-4" onclick="showQuestion({{ $index + 1 }})">
                                        Next <i class="fas fa-chevron-right ms-1"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success fw-bold px-4" data-bs-toggle="modal" data-bs-target="#submitModal">
                                        Finish & Submit <i class="fas fa-check-double ms-1"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Question Palette Sidebar -->
            <div class="col-lg-4 palette-sidebar">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 80px;">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-th me-2 text-primary"></i> Question Navigation Palette</h6>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4" id="paletteGrid">
                        @foreach($quiz->questions as $index => $question)
                            <button type="button" 
                                    class="palette-btn {{ $index === 0 ? 'current' : '' }}" 
                                    id="paletteBtn_{{ $index }}" 
                                    onclick="showQuestion({{ $index }})">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="p-3 bg-light rounded-3 text-muted extra-small">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="palette-btn answered" style="width: 24px; height: 24px;"></span> Answered
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="palette-btn unanswered" style="width: 24px; height: 24px;"></span> Unanswered / Visited
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="palette-btn" style="width: 24px; height: 24px;"></span> Not Visited
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-question-circle me-2"></i> Confirm Test Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-dark mb-3">Are you sure you want to submit this test? Your answers will be automatically evaluated.</p>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Continue Test</button>
                    <button type="button" id="confirmSubmitBtn" class="btn btn-success fw-bold px-4" onclick="submitTestNow()">
                        Yes, Submit Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let totalSeconds = {{ $quiz->duration_minutes }} * 60;
    let secondsElapsed = 0;
    let currentQIndex = 0;
    let totalQuestions = {{ $quiz->questions->count() }};
    let isSubmitted = false;

    // Countdown Timer Loop
    const timerInterval = setInterval(() => {
        totalSeconds--;
        secondsElapsed++;
        document.getElementById('timeTakenSeconds').value = secondsElapsed;

        if (totalSeconds <= 0 && !isSubmitted) {
            clearInterval(timerInterval);
            alert('Time is up! Submitting your test automatically.');
            submitTestNow();
        }

        const mins = Math.floor(Math.max(0, totalSeconds) / 60);
        const secs = Math.max(0, totalSeconds) % 60;
        document.getElementById('timerDisplay').innerText = 
            `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }, 1000);

    function showQuestion(index) {
        document.getElementById(`qBlock_${currentQIndex}`).style.display = 'none';
        document.getElementById(`paletteBtn_${currentQIndex}`).classList.remove('current');

        currentQIndex = index;

        document.getElementById(`qBlock_${currentQIndex}`).style.display = 'block';
        const currentBtn = document.getElementById(`paletteBtn_${currentQIndex}`);
        currentBtn.classList.add('current');

        if (!currentBtn.classList.contains('answered')) {
            currentBtn.classList.add('unanswered');
        }
    }

    function onOptionSelect(index) {
        const btn = document.getElementById(`paletteBtn_${index}`);
        btn.classList.remove('unanswered');
        btn.classList.add('answered');
    }

    function clearSelection(index, questionId) {
        const radios = document.getElementsByName(`answers[${questionId}]`);
        radios.forEach(r => r.checked = false);

        const btn = document.getElementById(`paletteBtn_${index}`);
        btn.classList.remove('answered');
        btn.classList.add('unanswered');
    }

    function submitTestNow() {
        if (isSubmitted) return;
        isSubmitted = true;
        const btn = document.getElementById('confirmSubmitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';
        }
        document.getElementById('testForm').submit();
    }
</script>
@endsection
