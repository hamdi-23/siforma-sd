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
                <form action="{{ route('daily-report.store') }}" method="POST" enctype="multipart/form-data">
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
                                <input type="text" name="class" class="form-control @error('class') is-invalid @enderror" value="{{ old('class') }}" placeholder="Contoh: VI A" required>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="learning_objectives" class="form-control @error('learning_objectives') is-invalid @enderror" rows="3" placeholder="Sebutkan tujuan pembelajaran" required>{{ old('learning_objectives') }}</textarea>
                        @error('learning_objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Materi Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="learning_materials" class="form-control @error('learning_materials') is-invalid @enderror" rows="4" placeholder="Sebutkan materi yang diajarkan" required>{{ old('learning_materials') }}</textarea>
                        @error('learning_materials')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="teaching_methods" class="form-control @error('teaching_methods') is-invalid @enderror" rows="3" placeholder="Contoh: Ceramah, Diskusi, Praktik" required>{{ old('teaching_methods') }}</textarea>
                        @error('teaching_methods')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Respons Siswa</label>
                        <textarea name="student_response" class="form-control @error('student_response') is-invalid @enderror" rows="3" placeholder="Bagaimana respons siswa terhadap pembelajaran?">{{ old('student_response') }}</textarea>
                        @error('student_response')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tugas yang Diberikan</label>
                        <textarea name="assignments_given" class="form-control @error('assignments_given') is-invalid @enderror" rows="3" placeholder="Sebutkan tugas yang diberikan kepada siswa">{{ old('assignments_given') }}</textarea>
                        @error('assignments_given')
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Siswa Hadir</label>
                                <input type="number" name="attendance_count" class="form-control @error('attendance_count') is-invalid @enderror" value="{{ old('attendance_count') }}" min="0">
                                @error('attendance_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Siswa</label>
                                <input type="number" name="total_students" class="form-control @error('total_students') is-invalid @enderror" value="{{ old('total_students') }}" min="0">
                                @error('total_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
