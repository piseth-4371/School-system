@extends('layouts.app')

@section('title', 'Attendance Details')
@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Student Attendance Details</h1>
                <div class="btn-group">
                    <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Attendance
                    </a>
                    <a href="{{ route('attendances.details.create', $attendance) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Student
                    </a>
                </div>
            </div>
            <p class="text-muted">Date: {{ $attendance->date->format('F d, Y') }} | Teacher: {{ $attendance->teacher->user->name ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Attendance Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Attendance Information</h5>
        </div>
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
        </div>
    </div>

    <!-- Attendance Details -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Student Attendance Details</h5>
            <span class="badge bg-primary">{{ $attendance->details->count() }} records</span>
        </div>
        <div class="card-body">
            @if($attendance->details->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No attendance details recorded yet.
                    <a href="{{ route('attendances.details.create', $attendance) }}" class="alert-link">Add attendance details</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Student Code</th>
                                <th>Student Name</th>
                                <th>Session 1</th>
                                <th>Session 2</th>
                                <th>Session 3</th>
                                <th>Session 4</th>
                                <th>Total Present</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendance->details as $detail)
                            <tr>
                                <td>{{ $detail->student->student_code ?? 'N/A' }}</td>
                                <td>{{ $detail->student->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $detail->session1 === 'present' ? 'success' : ($detail->session1 === 'absent' ? 'danger' : ($detail->session1 === 'late' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($detail->session1) }}
                                    </span>
                                </td>
                               <td>
                                    <span class="badge bg-{{ $detail->session2 === 'present' ? 'success' : ($detail->session2 === 'absent' ? 'danger' : ($detail->session2 === 'late' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($detail->session2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $detail->session3 === 'present' ? 'success' : ($detail->session3 === 'absent' ? 'danger' : ($detail->session3 === 'late' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($detail->session3) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $detail->session4 === 'present' ? 'success' : ($detail->session4 === 'absent' ? 'danger' : ($detail->session4 === 'late' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($detail->session4) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $presentCount = 0;
                                        if ($detail->session1 === 'present') $presentCount++;
                                        if ($detail->session2 === 'present') $presentCount++;
                                        if ($detail->session3 === 'present') $presentCount++;
                                        if ($detail->session4 === 'present') $presentCount++;
                                    @endphp
                                    <span class="badge bg-{{ $presentCount >= 3 ? 'success' : ($presentCount >= 2 ? 'warning' : 'danger') }}">
                                        {{ $presentCount }}/4
                                    </span>
                                </td>
                                <td>{{ $detail->remarks ?? '-' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('attendances.details.edit', $detail) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('attendances.details.destroy', $detail) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this attendance detail?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center">
                            <div class="card-body">
                                <h6 class="card-title">Full Attendance</h6>
                                <h3>{{ $attendance->details->where('session1', 'present')->where('session2', 'present')->where('session3', 'present')->where('session4', 'present')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center">
                            <div class="card-body">
                                <h6 class="card-title">Partial Absence</h6>
                                <h3>{{ $attendance->details->filter(function($detail) {
                                    $absent = 0;
                                    if ($detail->session1 === 'absent') $absent++;
                                    if ($detail->session2 === 'absent') $absent++;
                                    if ($detail->session3 === 'absent') $absent++;
                                    if ($detail->session4 === 'absent') $absent++;
                                    return $absent > 0 && $absent < 4;
                                })->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center">
                            <div class="card-body">
                                <h6 class="card-title">Full Absence</h6>
                                <h3>{{ $attendance->details->where('session1', 'absent')->where('session2', 'absent')->where('session3', 'absent')->where('session4', 'absent')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white text-center">
                            <div class="card-body">
                                <h6 class="card-title">Late Arrivals</h6>
                                <h3>{{ $attendance->details->filter(function($detail) {
                                    return $detail->session1 === 'late' || $detail->session2 === 'late' || 
                                           $detail->session3 === 'late' || $detail->session4 === 'late';
                                })->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection