@extends('layouts.admin')

@section('title', 'Test Attempt Logs | Admin')
@section('page-title', 'Student Test Attempt Log')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-history text-primary me-2"></i> All Student Quiz Submissions</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Quiz Title</th>
                    <th>Marks Obtained</th>
                    <th>Percentage</th>
                    <th>Time Taken</th>
                    <th>Attempt Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold text-dark">{{ $attempt->user->name ?? 'Student' }}</td>
                        <td>{{ $attempt->quiz->title ?? 'N/A' }}</td>
                        <td>{{ $attempt->marks_obtained }} / {{ $attempt->max_marks }}</td>
                        <td>
                            <span class="badge {{ $attempt->percentage >= ($attempt->quiz->pass_percentage ?? 40) ? 'bg-success' : 'bg-danger' }}">
                                {{ number_format($attempt->percentage, 1) }}%
                            </span>
                        </td>
                        <td>{{ $attempt->formatted_time_taken }}</td>
                        <td class="small">{{ $attempt->created_at->format('M d, Y h:i A') }}</td>
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
@endsection
