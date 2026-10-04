@extends('layouts.app')

@section('title', 'Attempt History | Student Portal')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold text-dark mb-4"><i class="fas fa-history text-primary me-2"></i> My Previous Test Attempts</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Quiz Title</th>
                        <th>Attempted Date</th>
                        <th>Marks Obtained</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $attempt->quiz->title ?? 'N/A' }}</td>
                            <td class="small">{{ $attempt->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $attempt->marks_obtained }} / {{ $attempt->max_marks }}</td>
                            <td>
                                <span class="badge {{ $attempt->percentage >= ($attempt->quiz->pass_percentage ?? 40) ? 'bg-success' : 'bg-danger' }}">
                                    {{ number_format($attempt->percentage, 1) }}%
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted border">{{ ucfirst($attempt->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('student.tests.result', [$attempt->quiz_id, $attempt->id]) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="fas fa-eye me-1"></i> Solution Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No test attempts logged yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection
