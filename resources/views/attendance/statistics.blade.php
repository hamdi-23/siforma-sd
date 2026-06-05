@extends('layouts.app')

@section('title', 'Statistik Presensi')

@section('content')
@php
    $totalDays = (int) ($stats->total_days ?? 0);
    $presentDays = (int) ($stats->present_days ?? 0);
    $absentDays = (int) ($stats->absent_days ?? 0);
    $lateDays = (int) ($stats->late_days ?? 0);
    $sickDays = (int) ($stats->sick_days ?? 0);
    $leaveDays = (int) ($stats->leave_days ?? 0);
    $presentRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;
@endphp

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-chart-pie"></i> Statistik Presensi</h1>
        <p>Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} sampai {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>
    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('attendance.statistics') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
                <a href="{{ route('attendance.statistics') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <i class="fas fa-calendar-days fa-2x text-primary"></i>
            <div class="stat-value">{{ $totalDays }}</div>
            <div class="stat-label">Total data presensi</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <i class="fas fa-user-check fa-2x text-success"></i>
            <div class="stat-value">{{ $presentRate }}%</div>
            <div class="stat-label">Rasio hadir</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <i class="fas fa-clock fa-2x text-warning"></i>
            <div class="stat-value">{{ $lateDays }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <i class="fas fa-user-xmark fa-2x text-danger"></i>
            <div class="stat-value">{{ $absentDays }}</div>
            <div class="stat-label">Absen</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-simple"></i> Distribusi Presensi</h5>
            </div>
            <div class="card-body">
                @php
                    $items = [
                        ['label' => 'Hadir / Terlambat', 'value' => $presentDays, 'class' => 'bg-success'],
                        ['label' => 'Absen', 'value' => $absentDays, 'class' => 'bg-danger'],
                        ['label' => 'Terlambat', 'value' => $lateDays, 'class' => 'bg-warning'],
                        ['label' => 'Sakit', 'value' => $sickDays, 'class' => 'bg-info'],
                        ['label' => 'Cuti', 'value' => $leaveDays, 'class' => 'bg-secondary'],
                    ];
                @endphp

                @foreach($items as $item)
                    @php $percentage = $totalDays > 0 ? round(($item['value'] / $totalDays) * 100) : 0; @endphp
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">{{ $item['label'] }}</span>
                            <span class="text-muted">{{ $item['value'] }} data</span>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar {{ $item['class'] }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-3">
                    <span>Hadir termasuk terlambat</span>
                    <strong class="text-success">{{ $presentDays }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-3">
                    <span>Sakit</span>
                    <strong class="text-info">{{ $sickDays }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-3">
                    <span>Cuti</span>
                    <strong class="text-secondary">{{ $leaveDays }}</strong>
                </div>
                <div class="d-flex justify-content-between py-3">
                    <span>Tanpa kehadiran</span>
                    <strong class="text-danger">{{ $absentDays }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
