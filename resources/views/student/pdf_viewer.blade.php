@extends('layouts.app')

@section('title', $material->title . ' | Protected PDF Reader')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('materials') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="fas fa-arrow-left me-1"></i> वापस PDF सूची पर जाएं
        </a>
        <span class="badge bg-danger px-3 py-2 fs-6">
            <i class="fas fa-shield-alt me-1"></i> सुरक्षित पठन (Protected Viewer)
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-truncate font-heading">{{ $material->title }}</h5>
            <small class="text-white-50"><i class="fas fa-file-pdf text-danger me-1"></i> {{ $material->subject->name ?? 'PDF Notes' }}</small>
        </div>
        <div class="card-body p-0 bg-secondary bg-opacity-10 text-center">
            <div class="ratio ratio-16x9" style="min-height: 75vh;">
                <iframe src="{{ route('materials.stream', $material->id) }}#toolbar=0&navpanes=0" title="{{ $material->title }}" style="border: none; width: 100%; height: 100%;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
