@extends('layouts.admin')

@section('title', 'Bulk PDF Upload | Admin')
@section('page-title', 'Bulk PDF Upload')

@section('content')
<div class="row g-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-3 mb-3 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fas fa-file-pdf text-danger me-2"></i> Bulk PDF Upload</h5>
                    <p class="text-muted small mb-0">Upload multiple PDF study notes together to the protected study material repository.</p>
                </div>
                <div>
                    <a href="{{ route('admin.materials') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Back to Study Materials
                    </a>
                </div>
            </div>

            @if(!isset($previewMode))
                <!-- STEP 1: UPLOAD FORM -->
                <form method="POST" action="{{ route('admin.materials.import.preview') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Choose Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Topic (Optional)</label>
                        <select name="topic_id" class="form-select">
                            <option value="">-- All Topics --</option>
                            @foreach($subjects as $subject)
                                @foreach($subject->topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $subject->name }} &rarr; {{ $topic->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Access Control <span class="text-danger">*</span></label>
                        <select name="access_type" class="form-select fw-bold" required>
                            <option value="free">Free Access</option>
                            <option value="membership">Membership Required (₹49 Plan)</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold small">Description (Optional)</label>
                        <input type="text" name="description" class="form-control" placeholder="Brief description applied to these imported PDF notes...">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold small">Select PDF Files <span class="text-danger">*</span></label>
                        <input type="file" name="pdf_files[]" class="form-control form-control-lg" accept=".pdf,application/pdf" multiple required>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i> You can select multiple <code>.pdf</code> files at once. Max file size: 50MB per file.
                        </small>
                    </div>
                    <div class="col-md-12 d-flex gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold px-4">
                            <i class="fas fa-search me-1"></i> Upload / Preview
                        </button>
                    </div>
                </form>
            @else
                <!-- STEP 2: PREVIEW & IMPORT SUMMARY -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <small class="text-muted d-block text-uppercase fw-bold">Total Files</small>
                            <span class="fs-3 fw-extrabold text-dark">{{ $totalFiles }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center border border-success">
                            <small class="text-success d-block text-uppercase fw-bold">Valid</small>
                            <span class="fs-3 fw-extrabold text-success">{{ $validCount }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 {{ $invalidCount > 0 ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light' }} rounded-3 text-center border">
                            <small class="{{ $invalidCount > 0 ? 'text-danger' : 'text-muted' }} d-block text-uppercase fw-bold">Invalid</small>
                            <span class="fs-3 fw-extrabold {{ $invalidCount > 0 ? 'text-danger' : 'text-dark' }}">{{ $invalidCount }}</span>
                        </div>
                    </div>
                </div>

                @if(!empty($validationErrors))
                    <div class="card border-danger mb-4">
                        <div class="card-header bg-danger text-white fw-bold">
                            <i class="fas fa-exclamation-triangle me-1"></i> Validation Warnings / Failed Files ({{ count($validationErrors) }})
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>File</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($validationErrors as $err)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $err['file_name'] }}</td>
                                            <td class="text-danger fw-semibold">{{ $err['error'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($validCount > 0)
                    <form method="POST" action="{{ route('admin.materials.import.execute') }}">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                        <input type="hidden" name="topic_id" value="{{ $selectedTopic ? $selectedTopic->id : '' }}">
                        <input type="hidden" name="access_type" value="{{ $accessType }}">
                        <input type="hidden" name="description" value="{{ $description }}">

                        <div class="table-responsive mb-4">
                            <table class="table table-hover border align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>File Name</th>
                                        <th>File Size</th>
                                        <th>Detected Title (Editable)</th>
                                        <th>Subject</th>
                                        <th>Access Type</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $validIndex = 0; @endphp
                                    @foreach($filePayloads as $index => $item)
                                        @if($item['status'] === 'valid')
                                            @php $validIndex++; @endphp
                                            <tr id="row-{{ $item['id'] }}">
                                                <td><span class="badge bg-light text-dark border">{{ $validIndex }}</span></td>
                                                <td><div class="fw-bold text-dark">{{ $item['original_name'] }}</div></td>
                                                <td><span class="badge bg-secondary bg-opacity-10 text-dark">{{ $item['size_formatted'] }}</span></td>
                                                <td>
                                                    <input type="text" name="items[{{ $index }}][title]" class="form-control form-control-sm fw-bold" value="{{ $item['title'] }}" required>
                                                    <input type="hidden" name="items[{{ $index }}][temp_path]" value="{{ $item['temp_path'] }}">
                                                    <input type="hidden" name="items[{{ $index }}][size_bytes]" value="{{ $item['size_bytes'] }}">
                                                </td>
                                                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $selectedSubject->name }}</span></td>
                                                <td>
                                                    @if($accessType === 'membership')
                                                        <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-lock me-1"></i> Membership</span>
                                                    @else
                                                        <span class="badge bg-success">Free</span>
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Valid</span></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('row-{{ $item['id'] }}').remove();">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                            <a href="{{ route('admin.materials.import') }}" class="btn btn-outline-secondary btn-lg fw-bold">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg fw-bold px-4">
                                <i class="fas fa-file-import me-1"></i> Confirm & Import PDF Notes
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4">
                        <a href="{{ route('admin.materials.import') }}" class="btn btn-primary fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> Go Back & Upload Valid Files
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
