@extends('layouts.app')

@section('title', 'Master Data Kelas')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-school"></i> Master Data Kelas</h1>
        <p>Kelola data kelas yang ada di sekolah</p>
    </div>
    @if(Auth::user()->isAdmin() || Auth::user()->isPrincipal())
        <a href="{{ route('classroom.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Kelas Baru
        </a>
    @endif
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        @if($classrooms->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kelas</th>
                            <th>Keterangan / Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classrooms as $classroom)
                            <tr>
                                <td>{{ $classroom->id }}</td>
                                <td><span class="fw-bold">{{ $classroom->name }}</span></td>
                                <td>{{ $classroom->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('classroom.edit', $classroom) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('classroom.destroy', $classroom) }}" method="POST" class="d-inline form-confirm-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $classrooms->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p class="text-muted text-center py-4">
                <i class="fas fa-inbox fa-2x mb-3"></i><br>
                Belum ada data kelas. Silakan tambah kelas baru.
            </p>
        @endif
    </div>
</div>
@endsection
