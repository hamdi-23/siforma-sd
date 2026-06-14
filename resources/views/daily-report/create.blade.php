@extends('layouts.app')

@section('title', 'Buat Laporan Harian')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Buat Laporan Pembelajaran Harian</h1>
    <p>Dokumentasikan pembelajaran harian Anda</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('daily-report.store') }}" method="POST" enctype="multipart/form-data" class="form-confirm-save">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Laporan <span class="text-danger">*</span></label>
                                <input type="date" name="report_date" class="form-control @error('report_date') is-invalid @enderror" value="{{ old('report_date', today()->toDateString()) }}" required>
                                @error('report_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="class" class="form-select @error('class') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->name }}" {{ old('class') == $classroom->name ? 'selected' : '' }}>
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Materi & Kegiatan Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="learning_materials" class="form-control @error('learning_materials') is-invalid @enderror" rows="4" placeholder="Sebutkan materi dan ringkasan kegiatan pembelajaran hari ini" required>{{ old('learning_materials') }}</textarea>
                        @error('learning_materials')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Materi Pembelajaran</label>
                        <input type="file" name="material_file" class="form-control @error('material_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <small class="form-text text-muted">
                            Format: PDF, Word, Excel, PowerPoint (Max 10MB)
                        </small>
                        @error('material_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Catatan atau keterangan lainnya">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <a href="{{ route('daily-report.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" name="submit" value="0" class="btn btn-warning">
                            <i class="fas fa-save"></i> Simpan sebagai Draft
                        </button>
                        <button type="submit" name="submit" value="1" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
