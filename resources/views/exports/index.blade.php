@extends('layouts.app')

@section('title', 'Riwayat Ekspor Data')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold"><i class="fas fa-file-download text-primary"></i> Riwayat Ekspor Data</h1>
        <p class="text-muted">Daftar file data yang Anda ekspor. File akan otomatis dihapus setelah 7 hari.</p>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Request</th>
                        <th>Jenis File</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exports as $export)
                        <tr>
                            <td>{{ $export->created_at->format('d M Y H:i:s') }}</td>
                            <td>
                                @if($export->type === 'excel')
                                    <span class="badge bg-success"><i class="fas fa-file-excel me-1"></i> Excel</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-file-pdf me-1"></i> PDF</span>
                                @endif
                            </td>
                            <td>
                                @if($export->status === 'pending')
                                    <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Menunggu...</span>
                                @elseif($export->status === 'processing')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-spinner fa-spin me-1"></i> Diproses...</span>
                                @elseif($export->status === 'completed')
                                    <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                @elseif($export->status === 'failed')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Gagal</span>
                                @endif
                            </td>
                            <td>
                                @if($export->status === 'completed' && $export->file_path)
                                    <a href="{{ route('exports.download', $export) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                        <i class="fas fa-download me-1"></i> Unduh File
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm" disabled>
                                        <i class="fas fa-download me-1"></i> Unduh File
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                <p class="mb-0">Belum ada riwayat ekspor data.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $exports->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    // Auto-refresh the page every 10 seconds if there are pending or processing exports
    @if($exports->whereIn('status', ['pending', 'processing'])->count() > 0)
        setTimeout(function() {
            window.location.reload();
        }, 10000);
    @endif
</script>
@endsection
