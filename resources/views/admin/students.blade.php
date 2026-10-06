@extends('layouts.admin')

@section('title', 'Manage Students | Admin')
@section('page-title', 'Registered Students & Membership Management')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-users text-primary me-2"></i> Registered Students List</h5>
        
        <form method="GET" action="{{ route('admin.students') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle extra-small">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email & Phone</th>
                    <th>Status</th>
                    <th>Membership Status</th>
                    <th>Account Action</th>
                    <th>Membership Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php $mem = $student->activeMembership(); @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold text-dark">{{ $student->name }}</td>
                        <td>
                            <div>{{ $student->email }}</div>
                            <small class="text-muted">{{ $student->phone ?: 'No phone' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $student->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td>
                            @if($mem)
                                <span class="badge bg-success"><i class="fas fa-crown me-1"></i> Active</span>
                                <div class="extra-small text-muted mt-1">Exp: {{ $mem->expires_at->format('d M Y') }}</div>
                            @else
                                <span class="badge bg-secondary">No Active Membership</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.students.toggle', $student->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $student->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} py-0">
                                    {{ $student->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('admin.students.grant_membership', $student->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning py-0 fw-bold text-dark" onclick="return confirm('Grant 1 Month Free Membership to {{ $student->name }}?')">
                                        <i class="fas fa-gift me-1"></i> Grant 1 Month Free
                                    </button>
                                </form>

                                @if($mem)
                                    <form method="POST" action="{{ route('admin.students.revoke_membership', $student->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0" onclick="return confirm('Revoke Membership for {{ $student->name }}?')">
                                            Revoke
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $students->links() }}
    </div>
</div>
@endsection
