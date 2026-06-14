@extends('layouts.app')

@section('title', 'Edit Master Kelas')

@section('content')
<div class="page-header mb-4">
    <a href="{{ route('classroom.index') }}" class="btn btn-light border btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <h1><i class="fas fa-edit"></i> Edit Data Kelas</h1>
    <p>Perbarui informasi kelas di bawah ini.</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('classroom.update', $classroom) }}" method="POST" class="form-confirm-edit">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $classroom->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Keterangan / Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $classroom->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
