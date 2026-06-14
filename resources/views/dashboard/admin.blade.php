@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('extra_css')
<style>
    .dashboard-shell {
        display: grid;
        gap: 1.5rem;
    }

    .dashboard-hero {
        background: #0f172a;
        border-radius: 16px;
        color: #fff;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::after {
        background: linear-gradient(135deg, rgba(14, 165, 233, .24), rgba(16, 185, 129, .18));
        content: "";
        inset: 0;
        position: absolute;
    }

    .dashboard-hero > * {
        position: relative;
        z-index: 1;
    }

    .dashboard-hero .eyebrow {
        color: #bae6fd;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .dashboard-hero h1 {
        font-size: clamp(1.8rem, 4vw, 2.65rem);
        font-weight: 800;
        margin: 8px 0;
    }

    .modern-card {
        background: var(--app-card-bg);
        border: 1px solid var(--app-border);
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, .06);
    }

    .metric-card {
        padding: 20px;
        min-height: 150px;
    }

    .metric-icon {
        align-items: center;
        border-radius: 12px;
        display: inline-flex;
        height: 44px;
        justify-content: center;
        width: 44px;
    }

    .metric-value {
        color: var(--app-dark);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-top: 18px;
    }

    .metric-label {
        color: var(--app-muted);
        font-size: .9rem;
        font-weight: 700;
        margin-top: 8px;
    }

    .section-card {
        padding: 22px;
    }

    .section-title {
        color: var(--app-dark);
        font-size: 1.1rem;
        font-weight: 800;
        margin: 0;
    }

    .soft-row {
        align-items: center;
        border: 1px solid var(--app-border);
        border-radius: 12px;
        display: grid;
        gap: 12px;
        grid-template-columns: minmax(0, 1fr) 170px 120px;
        margin-top: 12px;
        padding: 14px;
    }

    .quick-action {
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

    .quick-action:hover {
        border-color: #38bdf8;
        color: #0369a1;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 22px;
        }

        .soft-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $attendanceRate = $totalTeachers > 0 ? round(($todayStats['present'] / $totalTeachers) * 100) : 0;
    $recapRate = $totalTeachers > 0 ? round(($recapsCount / $totalTeachers) * 100) : 0;
@endphp

<div class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
            <div>
                <div class="eyebrow">Dashboard kepala sekolah</div>
                <h1>Monitoring operasional sekolah</h1>
                <p class="mb-0 text-white-50">Pantau presensi, laporan guru, dan rekap bulanan untuk {{ now()->format('d M Y') }}.</p>
            </div>
            <div class="d-flex align-items-start gap-2 flex-wrap">
                <a href="{{ route('attendance.statistics') }}" class="btn btn-light fw-semibold">
                    <i class="fas fa-chart-pie"></i> Statistik
                </a>
                <a href="{{ route('daily-report.index') }}" class="btn btn-outline-light fw-semibold">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
            </div>
        </div>
    </section>

    <section class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="modern-card metric-card">
                <span class="metric-icon bg-primary-subtle text-primary"><i class="fas fa-users"></i></span>
                <div class="metric-value">{{ $totalTeachers }}</div>
                <div class="metric-label">Guru aktif</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="modern-card metric-card">
                <span class="metric-icon bg-success-subtle text-success"><i class="fas fa-user-check"></i></span>
                <div class="metric-value">{{ $attendanceRate }}%</div>
                <div class="metric-label">{{ $todayStats['present'] }} hadir hari ini</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="modern-card metric-card">
                <span class="metric-icon bg-warning-subtle text-warning"><i class="fas fa-clock"></i></span>
                <div class="metric-value">{{ $teachersYetToReport }}</div>
                <div class="metric-label">Belum submit laporan</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="modern-card metric-card">
                <span class="metric-icon bg-info-subtle text-info"><i class="fas fa-chart-bar"></i></span>
                <div class="metric-value">{{ $recapRate }}%</div>
                <div class="metric-label">{{ $recapsCount }} dari {{ $totalTeachers }} rekap</div>
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-xl-7">
            <div class="modern-card section-card h-100">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <p class="text-muted fw-semibold mb-1">Presensi bulan {{ $currentMonth }}/{{ $currentYear }}</p>
                        <h2 class="section-title">Guru dengan presensi terendah</h2>
                    </div>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
                </div>

                @if($lowAttendanceTeachers->count() > 0)
                    @foreach($lowAttendanceTeachers as $teacher)
                        <div class="soft-row">
                            <div>
                                <div class="fw-bold">{{ $teacher->user->name }}</div>
                                <div class="text-muted small">NIP: {{ $teacher->nip ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="progress" style="height: 10px;">
                                    <div
                                        class="progress-bar {{ $teacher->attendance_percentage >= 80 ? 'bg-success' : ($teacher->attendance_percentage >= 60 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $teacher->attendance_percentage }}%"
                                    ></div>
                                </div>
                                <div class="small fw-semibold mt-1">{{ number_format($teacher->attendance_percentage, 1) }}%</div>
                            </div>
                            <a href="{{ route('attendance.index') }}?teacher={{ $teacher->id }}" class="btn btn-sm btn-light border">
                                Detail
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="rounded-3 bg-light p-4 text-muted">Semua guru memiliki presensi baik.</div>
                @endif
            </div>
        </div>

        <div class="col-xl-5">
            <div class="modern-card section-card mb-4">
                <p class="text-muted fw-semibold mb-1">Rangkuman hari ini</p>
                <h2 class="section-title mb-3">Status presensi</h2>
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="rounded-3 bg-light p-3">
                            <div class="h3 fw-bold text-primary mb-0">{{ $todayStats['total'] }}</div>
                            <div class="small text-muted">Total presensi</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-3 bg-light p-3">
                            <div class="h3 fw-bold text-danger mb-0">{{ $todayStats['absent'] }}</div>
                            <div class="small text-muted">Absen</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-3 bg-light p-3">
                            <div class="h3 fw-bold text-warning mb-0">{{ $todayStats['late'] }}</div>
                            <div class="small text-muted">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-3 bg-light p-3">
                            <div class="h3 fw-bold text-info mb-0">{{ $todayStats['sick'] + $todayStats['leave'] }}</div>
                            <div class="small text-muted">Sakit/Cuti</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card section-card">
                <p class="text-muted fw-semibold mb-1">Laporan guru</p>
                <h2 class="section-title mb-3">Status dokumen</h2>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Total laporan</span>
                    <strong>{{ $totalReports }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Dikirim</span>
                    <strong class="text-success">{{ $submittedReports }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Di-review</span>
                    <strong class="text-info">{{ $reviewedReports }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="modern-card section-card">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <p class="text-muted fw-semibold mb-1">Aksi cepat</p>
                <h2 class="section-title">Navigasi pekerjaan utama</h2>
            </div>
            <form method="POST" action="{{ route('monthly-recap.generate-all', [$currentYear, $currentMonth]) }}" onsubmit="return confirm('Generate recap untuk semua guru?')">
                @csrf
                <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="fas fa-sync"></i> Generate Rekap Semua Guru
                </button>
            </form>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-3">
                <a href="{{ route('attendance.index') }}" class="quick-action">
                    <i class="fas fa-list text-primary"></i> Data Presensi
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-report.index') }}" class="quick-action">
                    <i class="fas fa-file-alt text-success"></i> Laporan Guru
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('monthly-recap.index') }}" class="quick-action">
                    <i class="fas fa-chart-bar text-info"></i> Rekap Bulanan
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-report.index') }}?status=pending" class="quick-action">
                    <i class="fas fa-bell text-warning"></i> Belum Lapor
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
