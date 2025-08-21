@extends('layouts.app')

@section('title', 'Attendance Management')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <h1 class="h3 mb-0">Attendance Management</h1>
                <div class="d-flex gap-2">
                    <form action="{{ route('attendances.index') }}" method="GET" class="d-flex gap-2">
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
                        <select name="status" class="form-select form-select-sm" style="min-width: 140px">
                            <option value="">All Statuses</option>
                            <option value="present" @selected(request('status')==='present')>Present</option>
                            <option value="absent" @selected(request('status')==='absent')>Absent</option>
                        </select>
                        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-funnel"></i></button>
                        @if(request()->hasAny(['date','status']))
                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </form>
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                        <i class="bi bi-clipboard-plus"></i> Add Attendance
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->teacher->teacher_code ?? 'N/A' }}</td>
                            <td>{{ $attendance->teacher->user->name ?? 'N/A' }}</td>
                            <td>{{ $attendance->teacher->user->email ?? 'N/A' }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($attendance->date)->format('d M, Y') }}</td>
                            <td>
                                @php
                                    $isPresent = $attendance->status === 'present';
                                @endphp
                                <span class="badge {{ $isPresent ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this attendance record?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No attendance records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $attendances->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection