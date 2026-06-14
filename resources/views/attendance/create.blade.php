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
                <form action="{{ route('attendance.store') }}" method="POST" class="form-confirm-save">
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

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Jam masuk akan dicatat secara otomatis oleh sistem saat Anda menekan tombol Simpan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    @error('location')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="alert alert-warning" id="locationAlert" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> <span id="locationMessage">Mencari lokasi Anda...</span>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    const btnSubmit = document.getElementById('btnSubmit');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const locationAlert = document.getElementById('locationAlert');
    const locationMessage = document.getElementById('locationMessage');

    function checkLocationRequirement() {
        const status = statusSelect.value;
        if (status === 'present' || status === 'late') {
            btnSubmit.disabled = true;
            locationAlert.style.display = 'block';
            locationAlert.className = 'alert alert-warning mt-3';
            locationMessage.innerHTML = 'Mencari lokasi Anda... Mohon izinkan akses lokasi (GPS) pada browser.';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                        locationAlert.className = 'alert alert-success mt-3';
                        locationMessage.innerHTML = 'Lokasi ditemukan! Anda siap melakukan absensi.';
                        btnSubmit.disabled = false;
                    },
                    function(error) {
                        locationAlert.className = 'alert alert-danger mt-3';
                        let msg = 'Gagal mengambil lokasi.';
                        if (error.code === error.PERMISSION_DENIED) {
                            msg = 'Anda menolak akses lokasi. Aplikasi membutuhkan akses GPS untuk absensi.';
                        }
                        locationMessage.innerHTML = msg;
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                locationAlert.className = 'alert alert-danger mt-3';
                locationMessage.innerHTML = 'Browser Anda tidak mendukung fitur lokasi (GPS).';
            }
        } else {
            // Absen, sakit, cuti tidak butuh lokasi
            btnSubmit.disabled = false;
            locationAlert.style.display = 'none';
            latitudeInput.value = '';
            longitudeInput.value = '';
        }
    }

    statusSelect.addEventListener('change', checkLocationRequirement);
    
    // Run on load
    checkLocationRequirement();
});
</script>
@endpush
