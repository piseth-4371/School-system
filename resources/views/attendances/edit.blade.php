@extends('layouts.app')

@section('title', 'Edit Attendance')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Edit Attendance</h1>
            <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-triangle"></i></strong> Fix the issues below.
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('attendances.update', $attendance) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Teacher</label>
                    <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $attendance->teacher_id)==$teacher->id)>
                                {{ $teacher->teacher_code }} — {{ $teacher->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}"
                           class="form-control @error('date') is-invalid @enderror">
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="present" @selected(old('status', $attendance->status)==='present')>Present</option>
                        <option value="absent"  @selected(old('status', $attendance->status)==='absent')>Absent</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-success">
                        <i class="bi bi-check2-circle"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection