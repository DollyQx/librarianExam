@extends('layouts.app')

@section('title', 'PDF नोट्स | Bihar LET & Bihar Librarian Exam')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-file-pdf text-danger me-2"></i> PDF नोट्स (ऑनलाइन पठन)</h3>
            <p class="text-muted small mb-0">Bihar LET एवं Bihar Librarian परीक्षा की तैयारी के लिए महत्वपूर्ण नोट्स।</p>
        </div>

        <form method="GET" action="{{ route('materials') }}" class="d-flex gap-2">
            <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- सभी विषय (All Subjects) --</option>
                @foreach($subjects as $subj)
                    <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-4">
        @forelse($materials as $pdf)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-3">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-dark text-truncate mb-1">{{ $pdf->title }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-muted border">{{ $pdf->subject->name ?? 'General' }}</span>
                                    @if($pdf->isMembershipRequired())
                                        <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-lock me-1"></i> Membership Required</span>
                                    @else
                                        <span class="badge bg-success text-white">FREE</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-4">{{ Str::limit($pdf->description, 90) }}</p>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top extra-small text-muted mb-3">
                            <span><i class="fas fa-hdd me-1"></i> {{ $pdf->formatted_size }}</span>
                            <span><i class="fas fa-eye me-1"></i> सुरक्षित ऑनलाइन पठन</span>
                        </div>

                        @if($pdf->isMembershipRequired() && (!Auth::check() || !Auth::user()->hasActiveMembership()))
                            <a href="{{ route('membership.index') }}" class="btn btn-warning w-100 fw-bold text-dark rounded-3">
                                <i class="fas fa-crown me-2"></i> Membership लें
                            </a>
                        @else
                            <a href="{{ route('materials.view', $pdf->id) }}" class="btn btn-danger w-100 fw-bold rounded-3">
                                <i class="fas fa-book-open me-2"></i> PDF पढ़ें
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5 fs-5">कोई PDF सामग्री उपलब्ध नहीं है।</div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $materials->links() }}
    </div>
</div>
@endsection
