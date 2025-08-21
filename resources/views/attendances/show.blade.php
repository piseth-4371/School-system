@extends('layouts.app')

@section('title', 'Attendance Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Attendance Details - {{ $attendance->date->format('M d, Y') }}</h1>
                <div class="btn-group">
                    <a href="{{ route('attendances.details.show', $attendance) }}" class="btn btn-info">
                        <i class="bi bi-eye"></i> View Student Details
                    </a>
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <p class="text-muted">Teacher: {{ $attendance->teacher->user->name ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Date:</strong> {{ $attendance->date->format('F d, Y') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : 'danger' }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>Teacher:</strong> {{ $attendance->teacher->user->name ?? 'N/A' }}</p>
                    <p><strong>Teacher Code:</strong> {{ $attendance->teacher->teacher_code ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Department:</strong> {{ $attendance->teacher->department->name ?? 'N/A' }}</p>
                    <p><strong>Total Students:</strong> {{ $attendance->details->count() }}</p>
                </div>
            </div>
            
            @if($attendance->details->isEmpty())
                <div class="alert alert-info text-center mt-3">
                    <i class="bi bi-info-circle"></i> No student attendance records found.
                    <a href="{{ route('attendances.details.create', $attendance) }}" class="alert-link">Add student attendance</a>
                </div>
            @else
                <div class="mt-3">
                    <a href="{{ route('attendances.details.show', $attendance) }}" class="btn btn-primary">
                        <i class="bi bi-list-check"></i> View All Student Records ({{ $attendance->details->count() }})
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection