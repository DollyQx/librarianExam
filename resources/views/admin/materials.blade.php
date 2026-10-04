@extends('layouts.admin')

@section('title', 'Manage PDF Materials | Admin')
@section('page-title', 'PDF Study Material Repository')

@section('content')
<div class="row g-4">
    <!-- Upload PDF Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-upload text-danger me-2"></i> Upload Study PDF</h5>
            <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. AACR-2 Complete Rules PDF" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Summary of notes..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select PDF File <small class="text-muted">(Max 20MB)</small></label>
                    <input type="file" name="pdf_file" class="form-control" accept="application/pdf" required>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label fw-semibold" for="is_active">Publish PDF</label>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold">
                    <i class="fas fa-cloud-upload-alt me-2"></i> Upload PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-file-pdf text-danger me-2"></i> Uploaded PDF Files</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>PDF Title</th>
                            <th>Subject</th>
                            <th>Size</th>
                            <th>Downloads</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $pdf)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pdf->title }}</div>
                                    <small class="text-muted">{{ Str::limit($pdf->description, 40) }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $pdf->subject->name ?? 'General' }}</span></td>
                                <td class="small">{{ $pdf->formatted_size }}</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info fw-bold">{{ $pdf->downloads_count }}</span></td>
                                <td>
                                    <a href="{{ Storage::url($pdf->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-eye"></i></a>
                                    <form method="POST" action="{{ route('admin.materials.delete', $pdf->id) }}" onsubmit="return confirm('Delete this PDF file?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No PDF materials uploaded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $materials->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
