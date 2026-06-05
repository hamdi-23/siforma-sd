@extends('layouts.app')

@section('title', 'Edit Laporan Harian')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Edit Laporan Pembelajaran Harian</h1>
    <p>Perbarui laporan pembelajaran</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('daily-report.update', $dailyReport) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Laporan</label>
                                <input type="date" class="form-control" value="{{ $dailyReport->report_date->toDateString() }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <input type="text" name="class" class="form-control @error('class') is-invalid @enderror" value="{{ $dailyReport->class }}" required>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="learning_objectives" class="form-control @error('learning_objectives') is-invalid @enderror" rows="3" required>{{ $dailyReport->learning_objectives }}</textarea>
                        @error('learning_objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Materi Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="learning_materials" class="form-control @error('learning_materials') is-invalid @enderror" rows="4" required>{{ $dailyReport->learning_materials }}</textarea>
                        @error('learning_materials')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembelajaran <span class="text-danger">*</span></label>
                        <textarea name="teaching_methods" class="form-control @error('teaching_methods') is-invalid @enderror" rows="3" required>{{ $dailyReport->teaching_methods }}</textarea>
                        @error('teaching_methods')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Respons Siswa</label>
                        <textarea name="student_response" class="form-control @error('student_response') is-invalid @enderror" rows="3">{{ $dailyReport->student_response }}</textarea>
                        @error('student_response')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tugas yang Diberikan</label>
                        <textarea name="assignments_given" class="form-control @error('assignments_given') is-invalid @enderror" rows="3">{{ $dailyReport->assignments_given }}</textarea>
                        @error('assignments_given')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Materi Pembelajaran</label>
                        @if($dailyReport->material_file)
                            <div class="mb-2">
                                <small class="text-muted">File yang sudah di-upload:</small><br>
                                <a href="{{ asset('storage/' . $dailyReport->material_file) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i> {{ $dailyReport->material_file_original_name }}
                                </a>
                            </div>
                        @endif
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
                                <input type="number" name="attendance_count" class="form-control @error('attendance_count') is-invalid @enderror" value="{{ $dailyReport->attendance_count }}" min="0">
                                @error('attendance_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Siswa</label>
                                <input type="number" name="total_students" class="form-control @error('total_students') is-invalid @enderror" value="{{ $dailyReport->total_students }}" min="0">
                                @error('total_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ $dailyReport->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-end">
                        <a href="{{ route('daily-report.show', $dailyReport) }}" class="btn btn-secondary">
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
