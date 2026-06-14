@extends('layouts.app')

@section('title', isset($teacher) ? 'Edit Data Guru' : 'Tambah Guru')

@section('content')
<div class="page-header mb-4">
    <h1><i class="fas fa-{{ isset($teacher) ? 'user-edit' : 'user-plus' }}"></i> {{ isset($teacher) ? 'Edit Data Guru' : 'Tambah Guru Baru' }}</h1>
    <p>{{ isset($teacher) ? 'Perbarui informasi data diri dan akun guru' : 'Daftarkan akun dan profil guru baru ke dalam sistem' }}</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ isset($teacher) ? route('teachers.update', $teacher->id) : route('teachers.store') }}" method="POST" class="{{ isset($teacher) ? 'form-confirm-edit' : 'form-confirm-save' }}">
            @csrf
            @if(isset($teacher))
                @method('PUT')
            @endif

            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <h5 class="fw-bold border-bottom pb-2">A. Data Akun (Login)</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $teacher->user->name ?? '') }}" required placeholder="Contoh: Budi Santoso, S.Pd.">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-bold">Alamat Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $teacher->user->email ?? '') }}" required placeholder="Contoh: budi@sekolah.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @if(!isset($teacher))
                <div class="col-md-12">
                    <div class="alert alert-info py-2 mb-0">
                        <i class="fas fa-info-circle"></i> Password default untuk akun baru adalah: <strong>password</strong>
                    </div>
                </div>
                @endif
            </div>

            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <h5 class="fw-bold border-bottom pb-2">B. Profil Kepegawaian</h5>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nip" class="form-label fw-bold">NIP / NUPTK</label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $teacher->nip ?? '') }}" placeholder="Masukkan NIP jika ada">
                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="subject" class="form-label fw-bold">Tugas / Mata Pelajaran</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject', $teacher->subject ?? '') }}" placeholder="Contoh: Guru Kelas 1, Guru Penjaskes">
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $teacher->phone ?? '') }}" placeholder="Contoh: 08123456789">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hire_date" class="form-label fw-bold">Tanggal Bergabung</label>
                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror" id="hire_date" name="hire_date" value="{{ old('hire_date', isset($teacher) && $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '') }}">
                    @error('hire_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label fw-bold">Alamat Lengkap</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $teacher->address ?? '') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-bold">Status Kepegawaian</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', $teacher->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $teacher->status ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif / Pensiun</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('teachers.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($teacher) ? 'Simpan Perubahan' : 'Tambah Guru' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
