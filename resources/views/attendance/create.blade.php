@extends('layouts.app')

@section('title', 'Tambah Presensi')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Tambah Presensi</h1>
    <p>Input presensi baru</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', today()->toDateString()) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absen</option>
                            <option value="sick" {{ old('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Cuti</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Masuk (Check In)</label>
                                <input type="time" name="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror" value="{{ old('check_in_time') }}">
                                @error('check_in_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Keluar (Check Out)</label>
                                <input type="time" name="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror" value="{{ old('check_out_time') }}">
                                @error('check_out_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Presensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <p><strong>Status Presensi:</strong></p>
                <ul class="small">
                    <li><span class="badge badge-present">Hadir</span> - Datang tepat waktu</li>
                    <li><span class="badge badge-late">Terlambat</span> - Datang lebih dari jam 07:30</li>
                    <li><span class="badge badge-absent">Absen</span> - Tidak masuk tanpa keterangan</li>
                    <li><span class="badge badge-sick">Sakit</span> - Tidak masuk karena sakit</li>
                    <li><span class="badge badge-leave">Cuti</span> - Cuti resmi/izin</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
