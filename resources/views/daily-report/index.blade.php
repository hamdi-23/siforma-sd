@extends('layouts.app')

@section('title', 'Laporan Pembelajaran Harian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-file-alt"></i> Laporan Pembelajaran Harian</h1>
        <p>Kelola laporan pembelajaran harian guru</p>
    </div>
    @if(Auth::user()->isTeacher())
        <a href="{{ route('daily-report.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Buat Laporan Baru
        </a>
    @endif
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('daily-report.index') }}" class="row g-3">
            @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
                <div class="col-md-3">
                    <label class="form-label">Guru</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">Semua Guru</option>
                        @foreach(\App\Models\Teacher::all() as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Dikirim</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Di-review</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('daily-report.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        @if($reports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
                                <th>Nama Guru</th>
                            @endif
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Materi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
                                    <td>{{ $report->teacher->user->name }}</td>
                                @endif
                                <td>{{ $report->report_date->format('d M Y') }}</td>
                                <td>{{ $report->class }}</td>
                                <td>{{ Str::limit($report->learning_materials, 50) }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($report->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('daily-report.show', $report) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($report->status === 'draft' && Auth::user()->isTeacher())
                                        <a href="{{ route('daily-report.edit', $report) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if($report->status === 'submitted' && (Auth::user()->isAdmin() || Auth::user()->isPrincipal()))
                                        <form action="{{ route('daily-report.review', $report) }}" method="POST" class="d-inline form-confirm-save">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Review">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p class="text-muted text-center py-4">
                <i class="fas fa-inbox fa-2x mb-3"></i><br>
                Belum ada laporan
            </p>
        @endif
    </div>
</div>
@endsection
