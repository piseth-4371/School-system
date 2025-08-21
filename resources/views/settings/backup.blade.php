@extends('layouts.app')

@section('title', 'Backup Management - Settings')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Backup Management</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="bi bi-database-add display-4"></i>
                    </div>
                    <h5>Create Backup</h5>
                    <p class="text-muted">Create a full database backup</p>
                    <form action="{{ route('settings.backup.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>Restore Backup</h5>
                    <form action="{{ route('settings.backup.restore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Backup File</label>
                            <input type="file" class="form-control" name="backup_file" required>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-clockwise"></i> Restore
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Available Backups</h5>
        </div>
        <div class="card-body">
            @if($backupFiles && count($backupFiles) > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backupFiles as $file)
                        <tr>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ $file['date'] }}</td>
                            <td>
                                <a href="{{ route('settings.backup.download', $file['name']) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted">No backup files found.</p>
            @endif
        </div>
    </div>
</div>
@endsection