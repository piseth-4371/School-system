@extends('layouts.app')

@section('title', 'Add Attendance Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Add Attendance Details</h1>
                <a href="{{ route('attendances.details.show', $attendance) }}" class="btn btn-secondary">
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
                    <p><strong>Date:</strong> {{ $attendance->date->format('F d, Y') }}</p>
                    <p><strong>Teacher:</strong> {{ $attendance->teacher->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : 'danger' }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </p>
                    <p><strong>Department:</strong> {{ $attendance->teacher->department->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Student Attendance</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('attendances.details.store', $attendance) }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student *</label>
                            <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'N/A' }} ({{ $student->student_code ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 1 *</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session1" id="session1_present" value="present" checked>
                                    <label class="form-check-label text-success" for="session1_present">
                                        Present
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session1" id="session1_absent" value="absent">
                                    <label class="form-check-label text-danger" for="session1_absent">
                                        Absent
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session1" id="session1_late" value="late">
                                    <label class="form-check-label text-warning" for="session1_late">
                                        Late
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session1" id="session1_excused" value="excused">
                                    <label class="form-check-label text-secondary" for="session1_excused">
                                        Excused
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 2 *</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session2" id="session2_present" value="present" checked>
                                    <label class="form-check-label text-success" for="session2_present">
                                        Present
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session2" id="session2_absent" value="absent">
                                    <label class="form-check-label text-danger" for="session2_absent">
                                        Absent
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session2" id="session2_late" value="late">
                                    <label class="form-check-label text-warning" for="session2_late">
                                        Late
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session2" край id="session2_excused" value="excused">
                                    <label class="form-check-label text-secondary" for="session2_excused">
                                        Excused
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 3 *</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session3" id="session3_present" край value="present" checked>
                                    <label class="form-check-label text-success" for="session3_present">
                                        Present
                                    </ крайlabel>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session3" id="session3_absent" value="absent">
                                    <label class="form-check-label text-danger" for="session3_absent">
                                        Absent
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session3" id="session3_late" value="late">
                                    <label class="form-check-label text-warning" for="session3_l крайate">
                                        Late
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session3" id="session3_excused" value="excused">
                                    <label class="form-check-label text-secondary" for="session3_excused">
                                        Excused
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Session 4 *</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session4" id="session4_present" value="present" checked>
                                    <label class="form-check-label text-success" for="session4_present">
                                        Present
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio край" name="session4" id="session4_absent" value="absent">
                                    <label class="form-check-label text-danger" for="session4_absent">
                                        Absent
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session4" id="session4_late" value="late">
                                    <label class="form-check-label text-warning" for="session4_late">
                                        Late
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="session4" id="session4_excused" value="excused">
                                    <label class="form-check-label text-secondary" for="session4_excused">
                                        Excused
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Save Attendance Details
                </button>
            </form>
        </div>
    </div>
</div>
@endsection