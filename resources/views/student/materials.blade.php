@extends('layouts.app')

@section('title', 'Free PDF Study Materials | Student Portal')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-file-pdf text-danger me-2"></i> Free PDF Study Notes</h3>
            <p class="text-muted small mb-0">Download high quality notes for offline preparation.</p>
        </div>

        <form method="GET" action="{{ route('student.materials') }}" class="d-flex gap-2">
            <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- All Subjects --</option>
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-4">
        @forelse($materials as $pdf)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-3">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold text-dark text-truncate mb-1">{{ $pdf->title }}</h6>
                            <span class="badge bg-light text-muted border">{{ $pdf->subject->name ?? 'General' }}</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-4 flex-grow-1">{{ Str::limit($pdf->description, 90) }}</p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top extra-small text-muted mb-3">
                        <span><i class="fas fa-hdd me-1"></i> {{ $pdf->formatted_size }}</span>
                        <span><i class="fas fa-download me-1"></i> {{ $pdf->downloads_count }} Downloads</span>
                    </div>

                    <a href="{{ route('student.materials.download', $pdf->id) }}" class="btn btn-danger w-100 fw-bold rounded-3">
                        <i class="fas fa-download me-2"></i> Download Free PDF
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">No PDF materials found for this subject.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $materials->links() }}
    </div>
</div>
@endsection
