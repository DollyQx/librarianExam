@extends('layouts.admin')

@section('title', 'Bulk Quiz Import | Admin')
@section('page-title', 'Bulk Quiz Import (CSV/Excel)')

@section('content')
<div class="row g-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 mb-3 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fas fa-file-csv text-success me-2"></i> Excel / CSV Question Bank Import</h5>
                    <p class="text-muted small mb-0">Upload multiple quizzes and questions together using a structured CSV file.</p>
                </div>
                <div>
                    <a href="{{ route('admin.quizzes.import.template') }}" class="btn btn-outline-success btn-sm fw-bold">
                        <i class="fas fa-download me-1"></i> Download Excel Template (.csv)
                    </a>
                </div>
            </div>

            @if(!isset($previewMode))
                <!-- STEP 1: FILE UPLOAD -->
                <form method="POST" action="{{ route('admin.quizzes.import.preview') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label fw-bold small">Choose Excel / CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i> Supported format: UTF-8 CSV. Max file size: 5MB.
                        </small>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-search me-1"></i> Preview & Validate File
                        </button>
                    </div>
                </form>

                <div class="mt-4 p-3 bg-light rounded-3">
                    <h6 class="fw-bold text-dark mb-2">Column Format Guide:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-muted small bg-white mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Column Name</th>
                                    <th>Required</th>
                                    <th>Allowed Values / Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><code>quiz_title</code></td><td>Yes</td><td>Title of the mock test series</td></tr>
                                <tr><td><code>subject</code></td><td>Optional</td><td>e.g. Library Science, General Awareness</td></tr>
                                <tr><td><code>topic</code></td><td>Optional</td><td>e.g. Classification, Cataloguing</td></tr>
                                <tr><td><code>question</code></td><td>Yes</td><td>MCQ question text</td></tr>
                                <tr><td><code>option_a</code> ... <code>option_d</code></td><td>Yes</td><td>Options A, B, C, D text</td></tr>
                                <tr><td><code>correct_answer</code></td><td>Yes</td><td>Must be <code>A</code>, <code>B</code>, <code>C</code>, or <code>D</code></td></tr>
                                <tr><td><code>explanation</code></td><td>Optional</td><td>Solution explanation for students</td></tr>
                                <tr><td><code>marks</code></td><td>Optional</td><td>Numeric (Default: 1.0)</td></tr>
                                <tr><td><code>negative_marks</code></td><td>Optional</td><td>Numeric (Default: 0.25)</td></tr>
                                <tr><td><code>duration_minutes</code></td><td>Optional</td><td>Numeric (Default: 30)</td></tr>
                                <tr><td><code>access_type</code></td><td>Optional</td><td><code>free</code> or <code>membership</code></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- STEP 2: PREVIEW & VALIDATION SUMMARY -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <small class="text-muted d-block text-uppercase fw-bold">Total Rows</small>
                            <span class="fs-3 fw-extrabold text-dark">{{ $totalRows }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center border border-success">
                            <small class="text-success d-block text-uppercase fw-bold">Valid Rows</small>
                            <span class="fs-3 fw-extrabold text-success">{{ count($validRows) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 {{ count($validationErrors) > 0 ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light' }} rounded-3 text-center border">
                            <small class="{{ count($validationErrors) > 0 ? 'text-danger' : 'text-muted' }} d-block text-uppercase fw-bold">Validation Errors</small>
                            <span class="fs-3 fw-extrabold {{ count($validationErrors) > 0 ? 'text-danger' : 'text-dark' }}">{{ count($validationErrors) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-center border border-primary">
                            <small class="text-primary d-block text-uppercase fw-bold">Quizzes Detected</small>
                            <span class="fs-3 fw-extrabold text-primary">{{ count($quizTitles) }}</span>
                        </div>
                    </div>
                </div>

                @if(count($validationErrors) > 0)
                    <div class="alert alert-danger rounded-3 mb-4">
                        <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-1"></i> File Validation Failed ({{ count($validationErrors) }} errors)</h6>
                        <p class="mb-2 small">Import is blocked until all errors are fixed. Please review the errors below, fix your CSV file, and upload again.</p>
                    </div>

                    <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-striped table-hover border align-middle small">
                            <thead class="table-danger sticky-top">
                                <tr>
                                    <th style="width: 80px;">Row #</th>
                                    <th>Quiz Title</th>
                                    <th>Error Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($validationErrors as $err)
                                    <tr>
                                        <td><span class="badge bg-danger">Row {{ $err['row'] }}</span></td>
                                        <td class="fw-bold">{{ $err['quiz_title'] }}</td>
                                        <td class="text-danger fw-semibold">{{ $err['error'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('admin.quizzes.import') }}" class="btn btn-secondary fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Go Back & Upload Corrected File
                    </a>
                @else
                    <form method="POST" action="{{ route('admin.quizzes.import.execute') }}">
                        @csrf
                        <input type="hidden" name="csv_payload" value="{{ json_encode($validRows) }}">
                        @if(count($existingQuizzes) > 0)
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 mb-4">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="fas fa-copy text-warning me-1"></i> Duplicate Quiz Titles Detected ({{ count($existingQuizzes) }})
                                </h6>
                                <p class="small text-muted mb-2">The following quiz titles already exist in the database: <strong>{{ implode(', ', $existingQuizzes) }}</strong></p>
                                
                                <label class="form-label fw-bold small text-dark mb-1">Select Duplicate Handling Action:</label>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="duplicate_mode" id="mode_add" value="add_to_existing" checked>
                                    <label class="form-check-label small fw-semibold" for="mode_add">
                                        <strong>Add Questions to Existing Quiz</strong> (Appends questions into matching quizzes)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="duplicate_mode" id="mode_new" value="create_new">
                                    <label class="form-check-label small fw-semibold" for="mode_new">
                                        <strong>Create New Quiz</strong> (Creates separate new test series with timestamp)
                                    </label>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="duplicate_mode" value="create_new">
                        @endif

                        <div class="table-responsive mb-4" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-sm table-hover border align-middle small">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Row #</th>
                                        <th>Quiz Title</th>
                                        <th>Question Preview</th>
                                        <th>Subject</th>
                                        <th>Correct Answer</th>
                                        <th>Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($validRows, 0, 50) as $vRow)
                                        <tr>
                                            <td><span class="badge bg-light text-dark border">Row {{ $vRow['row_num'] }}</span></td>
                                            <td class="fw-bold text-dark">{{ Str::limit($vRow['quiz_title'], 30) }}</td>
                                            <td>{{ Str::limit($vRow['question'], 40) }}</td>
                                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $vRow['subject'] ?: 'General' }}</span></td>
                                            <td><span class="badge bg-success">Option {{ $vRow['correct_answer'] }}</span></td>
                                            <td>
                                                @if($vRow['access_type'] === 'membership')
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-lock me-1"></i> Membership</span>
                                                @else
                                                    <span class="badge bg-success">Free</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if(count($validRows) > 50)
                                <div class="text-center text-muted small py-2">... and {{ count($validRows) - 50 }} more valid rows.</div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg fw-bold px-4">
                                <i class="fas fa-check-circle me-1"></i> Confirm & Import {{ count($validRows) }} Questions
                            </button>
                            <a href="{{ route('admin.quizzes.import') }}" class="btn btn-outline-secondary btn-lg fw-bold">
                                Cancel
                            </a>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
