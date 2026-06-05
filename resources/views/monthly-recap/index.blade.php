@extends('layouts.app')

@section('title', 'Rekap Bulanan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-chart-bar"></i> Rekap Bulanan</h1>
        <p>Laporan rekapitulasi bulanan guru</p>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
        <div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
                <i class="fas fa-sync"></i> Generate Recap
            </a>
        </div>
    @endif
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('monthly-recap.index') }}" class="row g-3">
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
                <label class="form-label">Tahun</label>
                <input type="number" name="year" class="form-control" value="{{ request('year', now()->year) }}" min="2020">
            </div>

            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('monthly-recap.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        @if($recaps->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
                                <th>Nama Guru</th>
                            @endif
                            <th>Periode</th>
                            <th>Kehadiran</th>
                            <th>Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recaps as $recap)
                            <tr>
                                @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
                                    <td>{{ $recap->teacher->user->name }}</td>
                                @endif
                                <td>
                                    {{ \Carbon\Carbon::createFromFormat('m', $recap->month)->format('F') }} {{ $recap->year }}
                                </td>
                                <td>
                                    <div class="progress" style="height: 25px; min-width: 150px;">
                                        <div class="progress-bar {{ $recap->attendance_percentage >= 80 ? 'bg-success' : ($recap->attendance_percentage >= 60 ? 'bg-warning' : 'bg-danger') }}"
                                             role="progressbar"
                                             style="width: {{ $recap->attendance_percentage }}%"
                                             aria-valuenow="{{ $recap->attendance_percentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ $recap->attendance_percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $recap->total_reports_submitted }} Dikirim</span>
                                    <span class="badge bg-success">{{ $recap->total_reports_reviewed }} Reviewed</span>
                                </td>
                                <td>
                                    <a href="{{ route('monthly-recap.show', $recap) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $recaps->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p class="text-muted text-center py-4">
                <i class="fas fa-inbox fa-2x mb-3"></i><br>
                Belum ada rekap bulanan
            </p>
        @endif
    </div>
</div>

<!-- Generate Modal (for admin/principal) -->
@if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
    <div class="modal fade" id="generateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sync"></i> Generate Recap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form
                        id="generateForm"
                        method="POST"
                        action="{{ route('monthly-recap.generate-all', [now()->year, now()->month]) }}"
                    >
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" id="genYear" class="form-control" value="{{ now()->year }}" min="2020" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select id="genMonth" class="form-select" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ now()->month == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromFormat('m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="generateForm" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Generate untuk Semua Guru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('generateForm').addEventListener('submit', function(e) {
            const year = document.getElementById('genYear').value;
            const month = document.getElementById('genMonth').value;
            const url = "{{ route('monthly-recap.generate-all', ['_year', '_month']) }}"
                .replace('_year', year)
                .replace('_month', month);

            this.action = url;

            if (!confirm('Generate recap untuk semua guru bulan ' + month + '/' + year + '?')) {
                e.preventDefault();
            }
        });
    </script>
@endif
@endsection
