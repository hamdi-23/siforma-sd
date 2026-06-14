@extends('layouts.app')

@section('title', 'Dashboard - Guru')

@section('extra_css')
<style>
    .teacher-dashboard {
        display: grid;
        gap: 1.5rem;
    }

    .teacher-hero {
        background: #111827;
        border-radius: 16px;
        color: #fff;
        padding: 28px;
    }

    .teacher-hero h1 {
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        font-weight: 800;
        margin: 8px 0;
    }

    .teacher-card {
        background: var(--app-card-bg);
        border: 1px solid var(--app-border);
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, .06);
    }

    .teacher-metric {
        min-height: 148px;
        padding: 20px;
    }

    .teacher-metric .icon {
        align-items: center;
        border-radius: 12px;
        display: inline-flex;
        height: 44px;
        justify-content: center;
        width: 44px;
    }

    .teacher-metric .value {
        color: var(--app-dark);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-top: 18px;
    }

    .teacher-metric .label {
        color: var(--app-muted);
        font-size: .9rem;
        font-weight: 700;
        margin-top: 8px;
    }

    .panel {
        padding: 22px;
    }

    .panel-title {
        color: var(--app-dark);
        font-size: 1.1rem;
        font-weight: 800;
        margin: 0;
    }

    .action-tile {
        align-items: center;
        border: 1px solid var(--app-border);
        border-radius: 12px;
        color: var(--app-dark);
        display: flex;
        font-weight: 700;
        gap: 12px;
        padding: 15px;
        text-decoration: none;
        transition: .2s ease;
    }

    .action-tile:hover {
        border-color: #10b981;
        color: #047857;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
@php
    $presenceRate = $monthStats['total_days'] > 0 ? round(($monthStats['present_days'] / $monthStats['total_days']) * 100) : 0;
@endphp

<div class="teacher-dashboard">
    <section class="teacher-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
            <div>
                <div class="text-success fw-bold text-uppercase small">Dashboard guru</div>
                <h1>Halo, {{ Auth::user()->name }}</h1>
                <p class="mb-0 text-white-50">Kelola presensi, laporan harian, dan pantau performa bulanan Anda.</p>
            </div>
            <div class="d-flex align-items-start gap-2 flex-wrap">
                <a href="{{ route('attendance.create') }}" class="btn btn-light fw-semibold">
                    <i class="fas fa-plus"></i> Presensi
                </a>
                <a href="{{ route('daily-report.create') }}" class="btn btn-outline-light fw-semibold">
                    <i class="fas fa-file-medical"></i> Laporan
                </a>
            </div>
        </div>
    </section>

    <section class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="teacher-card teacher-metric">
                <span class="icon bg-primary-subtle text-primary"><i class="fas fa-clock"></i></span>
                <div class="value fs-5">
                    @if($todayAttendance)
                        <span class="badge badge-{{ strtolower($todayAttendance->status) }}">{{ ucfirst($todayAttendance->status) }}</span>
                    @else
                        <span class="badge bg-danger">Belum Input</span>
                    @endif
                </div>
                <div class="label">Presensi hari ini</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="teacher-card teacher-metric">
                <span class="icon bg-warning-subtle text-warning"><i class="fas fa-file"></i></span>
                <div class="value">{{ $draftReportsCount }}</div>
                <div class="label">Laporan draft</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="teacher-card teacher-metric">
                <span class="icon bg-success-subtle text-success"><i class="fas fa-user-check"></i></span>
                <div class="value">{{ $presenceRate }}%</div>
                <div class="label">Kehadiran bulan ini</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="teacher-card teacher-metric">
                <span class="icon bg-danger-subtle text-danger"><i class="fas fa-user-xmark"></i></span>
                <div class="value">{{ $monthStats['absent_days'] }}</div>
                <div class="label">Absen bulan ini</div>
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-xl-5">
            <div class="teacher-card panel h-100">
                <p class="text-muted fw-semibold mb-1">Statistik bulan ini</p>
                <h2 class="panel-title mb-4">Ringkasan presensi</h2>
                <div class="rounded-3 bg-light p-4 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Total hari kerja</span>
                        <strong class="fs-3 text-primary">{{ $monthStats['total_days'] }}</strong>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="rounded-3 border p-3">
                            <div class="h4 fw-bold text-success mb-0">{{ $monthStats['present_days'] }}</div>
                            <div class="small text-muted">Hadir</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-3 border p-3">
                            <div class="h4 fw-bold text-warning mb-0">{{ $monthStats['late_days'] }}</div>
                            <div class="small text-muted">Terlambat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="teacher-card panel h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <p class="text-muted fw-semibold mb-1">Rekap terakhir</p>
                        <h2 class="panel-title">Performa bulanan</h2>
                    </div>
                    @if($latestRecap)
                        <a href="{{ route('monthly-recap.show', $latestRecap) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    @endif
                </div>

                @if($latestRecap)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="rounded-3 bg-light p-4 h-100">
                                <div class="text-muted small fw-semibold">Periode</div>
                                <div class="fs-4 fw-bold">{{ $latestRecap->month }}/{{ $latestRecap->year }}</div>
                                <div class="mt-3 text-muted small fw-semibold">Kehadiran</div>
                                <span class="badge bg-success fs-6">{{ $latestRecap->attendance_percentage }}%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded-3 border p-4 h-100">
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Laporan dikirim</span>
                                    <strong>{{ $latestRecap->total_reports_submitted }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-2">
                                    <span>Laporan di-review</span>
                                    <strong>{{ $latestRecap->total_reports_reviewed }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-3 bg-light p-4 text-muted">Belum ada rekap bulanan.</div>
                @endif
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-xl-7">
            <div class="teacher-card panel h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <p class="text-muted fw-semibold mb-1">7 hari terakhir</p>
                        <h2 class="panel-title">Riwayat presensi</h2>
                    </div>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
                </div>

                @if($recentAttendance->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                    <tr>
                                        <td class="fw-semibold">{{ $attendance->date->format('d M Y') }}</td>
                                        <td><span class="badge badge-{{ strtolower($attendance->status) }}">{{ ucfirst($attendance->status) }}</span></td>
                                        <td>{{ $attendance->check_in_time ?? '-' }}</td>
                                        <td>{{ $attendance->check_out_time ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-3 bg-light p-4 text-muted">Belum ada data presensi.</div>
                @endif
            </div>
        </div>

        <div class="col-xl-5">
            <div class="teacher-card panel h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <p class="text-muted fw-semibold mb-1">Laporan</p>
                        <h2 class="panel-title">Terbaru</h2>
                    </div>
                    <a href="{{ route('daily-report.index') }}" class="btn btn-sm btn-outline-success">Semua</a>
                </div>

                @if($recentReports->count() > 0)
                    <div class="d-grid gap-3">
                        @foreach($recentReports as $report)
                            <div class="rounded-3 border p-3">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="fw-bold">{{ $report->report_date->format('d M Y') }}</div>
                                        <div class="small text-muted">Kelas {{ $report->class }}</div>
                                    </div>
                                    <span class="badge badge-{{ strtolower($report->status) }} align-self-start">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('daily-report.show', $report) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($report->status === 'draft')
                                        <a href="{{ route('daily-report.edit', $report) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3 bg-light p-4 text-muted">Belum ada laporan.</div>
                @endif
            </div>
        </div>
    </section>

    <section class="teacher-card panel">
        <p class="text-muted fw-semibold mb-1">Aksi cepat</p>
        <h2 class="panel-title mb-3">Mulai pekerjaan</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('attendance.create') }}" class="action-tile">
                    <i class="fas fa-plus text-primary"></i> Tambah Presensi
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-report.create') }}" class="action-tile">
                    <i class="fas fa-file-medical text-success"></i> Buat Laporan
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.index') }}" class="action-tile">
                    <i class="fas fa-list text-info"></i> Semua Presensi
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-report.index') }}" class="action-tile">
                    <i class="fas fa-folder-open text-warning"></i> Semua Laporan
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
