@extends('layouts.app')

@section('title', 'Detail Laporan Harian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-file-alt"></i> Detail Laporan Pembelajaran Harian</h1>
        <p>{{ $dailyReport->report_date->format('d M Y') }} - Kelas {{ $dailyReport->class }}</p>
    </div>
    <div>
        @if($dailyReport->status === 'draft')
            <a href="{{ route('daily-report.edit', $dailyReport) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
        <span class="badge badge-{{ strtolower($dailyReport->status) }} fs-5">
            {{ ucfirst(str_replace('_', ' ', $dailyReport->status)) }}
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label"><strong>Nama Guru</strong></label>
                        <p class="form-control-plaintext">{{ $dailyReport->teacher->user->name }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><strong>Mata Pelajaran</strong></label>
                        <p class="form-control-plaintext">{{ $dailyReport->teacher->subject ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><strong>Kelas</strong></label>
                        <p class="form-control-plaintext">{{ $dailyReport->class }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><strong>Tanggal</strong></label>
                        <p class="form-control-plaintext">{{ $dailyReport->report_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chalkboard"></i> Materi & Kegiatan Pembelajaran</h5>
            </div>
            <div class="card-body">
                <p>{{ $dailyReport->learning_materials }}</p>
                @if($dailyReport->material_file)
                    <hr>
                    <div>
                        <strong><i class="fas fa-file"></i> File Materi:</strong><br>
                        <a href="{{ asset('storage/' . $dailyReport->material_file) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-download"></i> {{ $dailyReport->material_file_original_name }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan Tambahan</h5>
            </div>
            <div class="card-body">
                <p>{{ $dailyReport->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 mb-4">
    <div class="col-md-12">
        <div class="d-grid gap-2 d-md-flex justify-content-end">
            <a href="{{ route('daily-report.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if($dailyReport->status === 'draft')
                <a href="{{ route('daily-report.edit', $dailyReport) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
            @if($dailyReport->status === 'submitted' && (Auth::user()->isAdmin() || Auth::user()->isPrincipal()))
                <form action="{{ route('daily-report.review', $dailyReport) }}" method="POST" class="d-inline form-confirm-save">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Review Laporan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Additional Info -->
<div class="row">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <p class="small text-muted mb-0">
                    <strong>Dibuat:</strong> {{ $dailyReport->created_at->format('d M Y H:i') }}<br>
                    <strong>Diperbarui:</strong> {{ $dailyReport->updated_at->format('d M Y H:i') }}<br>
                    @if($dailyReport->submitted_at)
                        <strong>Dikirim:</strong> {{ $dailyReport->submitted_at->format('d M Y H:i') }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
