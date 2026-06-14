@extends('layouts.app')

@section('title', 'Tambah Master Kelas')

@section('content')
<div class="page-header mb-4">
    <a href="{{ route('classroom.index') }}" class="btn btn-light border btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <h1><i class="fas fa-plus-circle"></i> Tambah Kelas Baru</h1>
    <p>Silakan isi detail kelas yang akan ditambahkan.</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('classroom.store') }}" method="POST" class="form-confirm-save">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Misal: Kelas 1A" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Keterangan / Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
