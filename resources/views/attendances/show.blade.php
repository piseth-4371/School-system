@extends('layouts.app')

@section('title', 'Attendance Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Attendance Details - {{ $attendance->date->format('M d, Y') }}</h1>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <p class="text-muted">Teacher: {{ $attendance->teacher->user->name }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Session 1</th>
                            <th>Session 2</th>
                            <th>Session 3</th>
                            <th>Session 4</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance->details as $detail)
                        <tr>
                            <td>{{ $detail->student->user->name }}</td>
                            <td>
                                <span class="badge bg-{{ $detail->session1 === 'present' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($detail->session1) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $detail->session2 === 'present' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($detail->session2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $detail->session3 === 'present' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($detail->session3) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $detail->session4 === 'present' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($detail->session4) }}
                                </span>
                            </td>
                            <td>{{ $detail->remarks ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection