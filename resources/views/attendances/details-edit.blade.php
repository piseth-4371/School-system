@extends('layouts.app')

@section('title', 'Edit Attendance Detail')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Edit Attendance Detail</h1>
                <a href="{{ route('attendances.details.show', $detail->attendance) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Details
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Attendance Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $detail->attendance->date->format('F d, Y') }}</p>
                    <p><strong>Teacher:</strong> {{ $detail->attendance->teacher->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Student:</strong> {{ $detail->student->user->name ?? 'N/A' }} ({{ $detail->student->student_code ?? 'N/A' }})</p>
                    <p>< крайstrong>Department:</strong> {{ $detail->attendance->teacher->department->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Attendance Sessions</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('attendances.details.update', $detail) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 1 *</label>
                            <div>
                                @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session1" 
                                           id="session1_{{ $status }}" value="{{ $status }}"
                                           {{ $detail->session1 === $status ? 'checked' : '' }}>
                                    <label class="form-check-label text-{{ $status === 'present' ? 'success' : ($status === 'absent' ? 'danger' : ($status === 'late' ? 'warning' : 'secondary')) }}"
                                           for="session1_{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class край="mb-3">
                            <label class="form-label">Session 2 *</label>
                            <div>
                                @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session2" 
                                           id="session2_{{ $status }}" value="{{ $status }}"
                                           {{ $detail->session2 === $status ? 'checked' : '' }}>
                                    <label class="form-check-label text-{{ $status === 'present' ? 'success' : ($status === 'absent' ? 'danger' : ($status === 'late' ? 'warning' : 'secondary')) }}"
                                           for="session2_{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 3 *</label>
                            <div>
                                @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session3" 
                                           id="session3_{{ $status }}" value="{{ $status }}"
                                           {{ $detail->session3 === $status ? 'checked' : '' }}>
                                    <label class="form-check-label text-{{ $status === 'present' ? 'success' : ($status === 'absent' ? 'danger' : ($status === 'late' ? 'warning' : 'secondary')) }}"
                                           for="session3_{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 4 *</label>
                            <div>
                                @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session4" 
                                           id="session4_{{ $status }}" value="{{ $status }}"
                                           {{ $detail->session4 === $status ? 'checked' : '' }}>
                                    <label class="form-check-label text-{{ $status === 'present' ? 'success' : ($status === 'absent' ? 'danger' : ($status === 'late' ? 'warning' : 'secondary')) }}"
                                           for="session4_{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $detail->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit край" class="btn btn-primary">
                    <i class край="bi bi-check-circle"></i> Update Attendance
                </button>
            </form>
        </div>
    </div>
</div>
@endsection