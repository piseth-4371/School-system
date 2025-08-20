@extends('layouts.app')

@section('title', 'System Configuration')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">System Configuration</h1>
                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Settings
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">School Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.system-config.update') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_name" class="form-label">School Name *</label>
                                    <input type="text" class="form-control" id="school_name" name="school_name" 
                                           value="{{ old('school_name', $config['school_name']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_email" class="form-label">School Email</label>
                                    <input type="email" class="form-control" id="school_email" name="school_email" 
                                           value="{{ old('school_email', $config['school_email']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="school_address" class="form-label">School Address</label>
                            <textarea class="form-control" id="school_address" name="school_address" rows="2">{{ old('school_address', $config['school_address']) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="school_phone" class="form-label">School Phone</label>
                                    <input type="text" class="form-control" id="school_phone" name="school_phone" 
                                           value="{{ old('school_phone', $config['school_phone']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Currency *</label>
                                    <select class="form-select" id="currency" name="currency" required>
                                        <option value="USD" {{ old('currency', $config['currency']) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ old('currency', $config['currency']) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="GBP" {{ old('currency', $config['currency']) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                        <option value="KHR" {{ old('currency', $config['currency']) == 'KHR' ? 'selected' : '' }}>KHR (៛)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format *</label>
                                    <select class="form-select" id="date_format" name="date_format" required>
                                        <option value="Y-m-d" {{ old('date_format', $config['date_format']) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                        <option value="d-m-Y" {{ old('date_format', $config['date_format']) == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                        <option value="m-d-Y" {{ old('date_format', $config['date_format']) == 'm-d-Y' ? 'selected' : '' }}>MM-DD-YYYY</option>
                                        <option value="Y/m/d" {{ old('date_format', $config['date_format']) == 'Y/m/d' ? 'selected' : '' }}>YYYY/MM/DD</option>
                                        <option value="d/m/Y" {{ old('date_format', $config['date_format']) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                        <option value="m/d/Y" {{ old('date_format', $config['date_format']) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Configuration
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>School Name:</strong><br>
                        {{ $config['school_name'] }}
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong><br>
                        {{ $config['school_address'] ?: 'Not set' }}
                    </div>
                    <div class="mb-3">
                        <strong>Contact:</strong><br>
                        {{ $config['school_phone'] ?: 'Not set' }}<br>
                        {{ $config['school_email'] ?: 'Not set' }}
                    </div>
                    <div class="mb-3">
                        <strong>Currency:</strong> {{ $config['currency'] }}
                    </div>
                    <div>
                        <strong>Date Format:</strong> {{ $config['date_format'] }}
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Laravel Version:</strong> {{ Illuminate\Foundation\Application::VERSION }}
                    </div>
                    <div class="mb-2">
                        <strong>PHP Version:</strong> {{ PHP_VERSION }}
                    </div>
                    <div>
                        <strong>Environment:</strong> {{ app()->environment() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection