@extends('layouts.app')

@section('title', 'Edit Presensi')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Edit Presensi</h1>
    <p>Perbarui data presensi</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $attendance->date->toDateString() }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absen</option>
                            <option value="sick" {{ $attendance->status == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="leave" {{ $attendance->status == 'leave' ? 'selected' : '' }}>Cuti</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Masuk (Check In)</label>
                                <input type="time" name="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror" value="{{ $attendance->check_in_time }}">
                                @error('check_in_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Keluar (Check Out)</label>
                                <input type="time" name="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror" value="{{ $attendance->check_out_time }}">
                                @error('check_out_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ $attendance->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <a href="{{ route('attendance.show', $attendance) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detail Presensi</h5>
            </div>
            <div class="card-body">
                <p><strong>Tanggal:</strong> {{ $attendance->date->format('d M Y') }}</p>
                <p><strong>Status Saat Ini:</strong> <span class="badge badge-{{ strtolower($attendance->status) }}">{{ ucfirst($attendance->status) }}</span></p>
                <p class="small text-muted mb-0">Dibuat: {{ $attendance->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
