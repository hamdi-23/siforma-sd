@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><i class="fas fa-users"></i> Data Guru</h1>
        <p>Manajemen data akun dan profil guru</p>
    </div>
    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Guru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama & NIP</th>
                        <th>Email & Kontak</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $index => $teacher)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $teacher->user->name }}</div>
                            <div class="small text-muted">{{ $teacher->nip ?? '-' }}</div>
                        </td>
                        <td>
                            <div>{{ $teacher->user->email }}</div>
                            <div class="small text-muted">{{ $teacher->phone ?? '-' }}</div>
                        </td>
                        <td>{{ $teacher->subject ?? '-' }}</td>
                        <td>
                            @if($teacher->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-inline form-confirm-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data guru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
