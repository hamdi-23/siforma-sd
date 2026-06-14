<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Presensi Guru</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .filter-info {
            margin-bottom: 20px;
        }
        .filter-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-present { color: #16a34a; }
        .status-late { color: #ca8a04; }
        .status-absent { color: #dc2626; }
        .status-sick { color: #2563eb; }
        .status-leave { color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Presensi Guru</h1>
        <p>Siforma SD - Sistem Manajemen Sekolah Dasar</p>
    </div>

    <div class="filter-info">
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}</p>
        @if(request('start_date') || request('end_date'))
            <p><strong>Periode:</strong> {{ request('start_date', 'Awal') }} s/d {{ request('end_date', 'Akhir') }}</p>
        @endif
        @if(request('search'))
            <p><strong>Pencarian:</strong> {{ request('search') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Guru</th>
                <th>NIP</th>
                <th>Kelas</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusLabels = [
                    'present' => 'Hadir',
                    'late' => 'Terlambat',
                    'absent' => 'Absen',
                    'sick' => 'Sakit',
                    'leave' => 'Cuti',
                ];
            @endphp
            @forelse($attendances as $index => $attendance)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                    <td>{{ $attendance->teacher->user->name ?? '-' }}</td>
                    <td>{{ $attendance->teacher->nip ?? '-' }}</td>
                    <td>{{ $attendance->teacher->class_name ?? '-' }}</td>
                    <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                    <td class="status-{{ $attendance->status }}">
                        {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                    </td>
                    <td>{{ $attendance->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data presensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
