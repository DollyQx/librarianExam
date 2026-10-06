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
                    <label class="form-label fw-bold small">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. AACR-2 Complete Rules PDF" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
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
                <div class="mb-3">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Summary of notes..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Select PDF File <small class="text-muted">(Max 20MB)</small></label>
                    <input type="file" name="pdf_file" class="form-control" accept="application/pdf" required>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold small" for="is_active">Publish PDF (Active)</label>
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
                            <th>Order</th>
                            <th>PDF Title</th>
                            <th>Subject</th>
                            <th>Access Type</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $pdf)
                            <tr>
                                <td><span class="badge bg-light text-dark border">{{ $pdf->sort_order }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $pdf->title }}</div>
                                    <small class="text-muted">{{ Str::limit($pdf->description, 40) }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $pdf->subject->name ?? 'General' }}</span></td>
                                <td>
                                    @if($pdf->isMembershipRequired())
                                        <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-lock me-1"></i> Membership</span>
                                    @else
                                        <span class="badge bg-success">Free</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $pdf->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }} fw-bold">
                                        {{ $pdf->is_active ? 'Active' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editMaterialModal{{ $pdf->id }}" title="Edit PDF Info"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('admin.materials.delete', $pdf->id) }}" onsubmit="return confirm('Delete this PDF file?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete PDF"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editMaterialModal{{ $pdf->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit PDF Material</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.materials.update', $pdf->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Title</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $pdf->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Subject</label>
                                                    <select name="subject_id" class="form-select" required>
                                                        @foreach($subjects as $subj)
                                                            <option value="{{ $subj->id }}" {{ $pdf->subject_id == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Access Control</label>
                                                    <select name="access_type" class="form-select fw-bold">
                                                        <option value="free" {{ $pdf->access_type === 'free' ? 'selected' : '' }}>Free Access</option>
                                                        <option value="membership" {{ $pdf->access_type === 'membership' ? 'selected' : '' }}>Membership Required (₹49 Plan)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $pdf->sort_order }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="description" rows="2" class="form-control">{{ $pdf->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Replace PDF File <small class="text-muted">(Optional)</small></label>
                                                    <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="edit_material_active{{ $pdf->id }}" value="1" {{ $pdf->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold small" for="edit_material_active{{ $pdf->id }}">Active (Published)</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Update Material</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No PDF materials uploaded yet.</td></tr>
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
