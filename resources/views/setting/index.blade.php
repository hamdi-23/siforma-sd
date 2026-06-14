@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold"><i class="fas fa-cog text-primary"></i> Pengaturan Sistem</h1>
        <p class="text-muted">Kelola parameter dan aturan baku aplikasi</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('setting.store') }}" method="POST" class="form-confirm-save">
            @csrf
            
            <!-- Pengaturan Jam -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-clock me-2"></i> Aturan Jam Kehadiran</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Batas Jam Masuk (Terlambat) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-sign-in-alt"></i></span>
                                <input type="time" name="attendance_time_in" class="form-control form-control-lg @error('attendance_time_in') is-invalid @enderror" value="{{ old('attendance_time_in', $settings['attendance_time_in']) }}" required>
                            </div>
                            <small class="form-text text-muted mt-2 d-block">Lewat dari jam ini, guru dianggap <b>Terlambat</b>.</small>
                            @error('attendance_time_in')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Batas Jam Pulang (Cepat) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-sign-out-alt"></i></span>
                                <input type="time" name="attendance_time_out" class="form-control form-control-lg @error('attendance_time_out') is-invalid @enderror" value="{{ old('attendance_time_out', $settings['attendance_time_out']) }}" required>
                            </div>
                            <small class="form-text text-muted mt-2 d-block">Keluar sebelum jam ini dianggap <b>Tidak tepat waktu</b>.</small>
                            @error('attendance_time_out')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Lokasi -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-map-marker-alt me-2"></i> Pengaturan Lokasi Sekolah (GPS)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary bg-primary-subtle border-primary border-opacity-25 rounded-3 mb-4">
                        <div class="d-flex">
                            <div class="me-3 mt-1">
                                <i class="fas fa-info-circle fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading fw-bold text-primary mb-1">Cara Mengisi Titik Koordinat:</h6>
                                <p class="mb-0 small">Gunakan tombol <b>"Ambil Lokasi Saat Ini"</b> jika Anda sedang berada di sekolah sekarang. Atau, Anda bisa mengisinya manual dengan menyalin angka koordinat dari Google Maps.</p>
                                <button type="button" class="btn btn-primary btn-sm mt-3 shadow-sm rounded-pill px-3" id="btnGetLocation">
                                    <i class="fas fa-location-crosshairs me-1"></i> Ambil Lokasi Saat Ini
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Latitude (Garis Lintang) <span class="text-danger">*</span></label>
                            <input type="text" id="school_latitude" name="school_latitude" class="form-control form-control-lg bg-light @error('school_latitude') is-invalid @enderror" value="{{ old('school_latitude', $settings['school_latitude']) }}" required placeholder="-6.200000">
                            @error('school_latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Longitude (Garis Bujur) <span class="text-danger">*</span></label>
                            <input type="text" id="school_longitude" name="school_longitude" class="form-control form-control-lg bg-light @error('school_longitude') is-invalid @enderror" value="{{ old('school_longitude', $settings['school_longitude']) }}" required placeholder="106.816666">
                            @error('school_longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4 text-muted">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Batas Radius Absensi <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <input type="number" name="allowed_radius_meters" min="10" class="form-control bg-light @error('allowed_radius_meters') is-invalid @enderror" value="{{ old('allowed_radius_meters', $settings['allowed_radius_meters']) }}" required>
                            <span class="input-group-text">Meter</span>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">Jarak maksimal (dalam meter) yang diizinkan agar guru bisa melakukan presensi. Disarankan <b>50 - 100 meter</b> agar wajar.</small>
                        @error('allowed_radius_meters')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mb-5">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm fw-bold">
                    <i class="fas fa-save me-2"></i> Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGetLocation = document.getElementById('btnGetLocation');
    const latInput = document.getElementById('school_latitude');
    const lngInput = document.getElementById('school_longitude');

    btnGetLocation.addEventListener('click', function() {
        btnGetLocation.disabled = true;
        btnGetLocation.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mendapatkan lokasi...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latInput.value = position.coords.latitude;
                    lngInput.value = position.coords.longitude;
                    
                    // Highlight inputs to show they changed
                    latInput.classList.add('is-valid');
                    lngInput.classList.add('is-valid');
                    
                    setTimeout(() => {
                        latInput.classList.remove('is-valid');
                        lngInput.classList.remove('is-valid');
                    }, 2000);

                    btnGetLocation.disabled = false;
                    btnGetLocation.innerHTML = '<i class="fas fa-check-circle me-1"></i> Berhasil Ambil Lokasi';
                    btnGetLocation.classList.replace('btn-primary', 'btn-success');
                    
                    setTimeout(() => {
                        btnGetLocation.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Ambil Lokasi Saat Ini';
                        btnGetLocation.classList.replace('btn-success', 'btn-primary');
                    }, 3000);
                },
                function(error) {
                    alert('Gagal mengambil lokasi. Pastikan Anda mengizinkan akses lokasi (GPS) pada browser.');
                    btnGetLocation.disabled = false;
                    btnGetLocation.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Ambil Lokasi Saat Ini';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert('Browser Anda tidak mendukung fitur lokasi (GPS).');
            btnGetLocation.disabled = false;
            btnGetLocation.innerHTML = '<i class="fas fa-location-crosshairs me-1"></i> Ambil Lokasi Saat Ini';
        }
    });
});
</script>
@endpush
