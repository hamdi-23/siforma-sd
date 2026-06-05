@extends('layouts.app')

@section('title', 'Detail Rekap Bulanan')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Rekap Bulanan</h1>
    <p>{{ \Carbon\Carbon::createFromFormat('m', $monthlyRecap->month)->format('F') }} {{ $monthlyRecap->year }} - {{ $monthlyRecap->teacher->user->name }}</p>
</div>

<!-- Main Stats -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stat-card">
            <i class="fas fa-calendar fa-2x" style="color: #3498db;"></i>
            <div class="stat-label">Total Hari</div>
            <div class="stat-value">{{ $monthlyRecap->total_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card success">
            <i class="fas fa-check fa-2x"></i>
            <div class="stat-label">Hadir</div>
            <div class="stat-value">{{ $monthlyRecap->present_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card danger">
            <i class="fas fa-times fa-2x"></i>
            <div class="stat-label">Absen</div>
            <div class="stat-value">{{ $monthlyRecap->absent_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card warning">
            <i class="fas fa-hourglass-end fa-2x"></i>
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $monthlyRecap->late_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card" style="background: #ecf0f1;">
            <i class="fas fa-hospital fa-2x" style="color: #16a085;"></i>
            <div class="stat-label">Sakit</div>
            <div class="stat-value">{{ $monthlyRecap->sick_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card" style="background: #ecf0f1;">
            <i class="fas fa-umbrella fa-2x" style="color: #9b59b6;"></i>
            <div class="stat-label">Cuti</div>
            <div class="stat-value">{{ $monthlyRecap->leave_days }}</div>
        </div>
    </div>
</div>

<!-- Attendance Summary -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Ringkasan Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Persentase Kehadiran</span>
                        <strong>{{ $monthlyRecap->attendance_percentage }}%</strong>
                    </div>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar {{ $monthlyRecap->attendance_percentage >= 80 ? 'bg-success' : ($monthlyRecap->attendance_percentage >= 60 ? 'bg-warning' : 'bg-danger') }}"
                             role="progressbar"
                             style="width: {{ $monthlyRecap->attendance_percentage }}%"
                             aria-valuenow="{{ $monthlyRecap->attendance_percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <table class="table table-sm">
                    <tr>
                        <td>Hadir :</td>
                        <td><strong>{{ $monthlyRecap->present_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Terlambat :</td>
                        <td><strong>{{ $monthlyRecap->late_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Absen :</td>
                        <td><strong>{{ $monthlyRecap->absent_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Sakit :</td>
                        <td><strong>{{ $monthlyRecap->sick_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Cuti :</td>
                        <td><strong>{{ $monthlyRecap->leave_days }}</strong> hari</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Reports Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> Ringkasan Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-md-6">
                        <h6>Laporan Dikirim</h6>
                        <h3 class="text-primary">{{ $monthlyRecap->total_reports_submitted }}</h3>
                    </div>
                    <div class="col-md-6">
                        <h6>Laporan Di-review</h6>
                        <h3 class="text-success">{{ $monthlyRecap->total_reports_reviewed }}</h3>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong><br>
                    Total laporan pembelajaran harian yang dikirim: <strong>{{ $monthlyRecap->total_reports_submitted }}</strong><br>
                    Laporan yang sudah di-review: <strong>{{ $monthlyRecap->total_reports_reviewed }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i> Ringkasan Bulanan</h5>
            </div>
            <div class="card-body">
                @if($monthlyRecap->summary)
                    <p>{{ $monthlyRecap->summary }}</p>
                @else
                    <p class="text-muted">Tidak ada ringkasan</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Rekap Info -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <p class="small text-muted mb-0">
                    <strong>Data Guru:</strong> {{ $monthlyRecap->teacher->user->name }}<br>
                    <strong>NIP:</strong> {{ $monthlyRecap->teacher->nip ?? '-' }}<br>
                    <strong>Mata Pelajaran:</strong> {{ $monthlyRecap->teacher->subject ?? '-' }}<br>
                    <strong>Rekap Dibuat:</strong> {{ $monthlyRecap->generated_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4 mb-4">
    <div class="col-md-12">
        <div class="d-grid gap-2 d-md-flex justify-content-end">
            <a href="{{ route('monthly-recap.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
</div>
@endsection
