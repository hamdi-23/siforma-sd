@extends('layouts.app')

@section('title', 'Detail Presensi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-eye"></i> Detail Presensi</h1>
        <p>Tanggal: {{ $attendance->date->format('d M Y') }}</p>
    </div>
    @if(Auth::user()->isTeacher())
        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endif
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Informasi Presensi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Guru</label>
                        <p class="form-control-plaintext">{{ $attendance->teacher->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIP</label>
                        <p class="form-control-plaintext">{{ $attendance->teacher->nip ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <p class="form-control-plaintext">{{ $attendance->date->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ strtolower($attendance->status) }} fs-6">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jam Masuk (Check In)</label>
                        <p class="form-control-plaintext">{{ $attendance->check_in_time ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Keluar (Check Out)</label>
                        <p class="form-control-plaintext">{{ $attendance->check_out_time ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <p class="form-control-plaintext">{{ $attendance->notes ?? '-' }}</p>
                </div>

                <hr>

                <div class="d-grid gap-2 d-md-flex justify-content-end">
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if(Auth::user()->isTeacher())
                        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <p><strong>Mata Pelajaran:</strong> {{ $attendance->teacher->subject ?? '-' }}</p>
                <p><strong>Status Guru:</strong> <span class="badge bg-{{ $attendance->teacher->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($attendance->teacher->status) }}</span></p>
                <hr>
                <p class="small text-muted mb-0">
                    <strong>Dibuat:</strong> {{ $attendance->created_at->format('d M Y H:i') }}<br>
                    <strong>Diperbarui:</strong> {{ $attendance->updated_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
