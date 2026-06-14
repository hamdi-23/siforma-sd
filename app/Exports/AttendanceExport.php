<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        // Add sorting by date descending and eager load relationships
        return $this->query->with(['teacher.user'])->orderBy('date', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Guru',
            'NIP',
            'Kelas',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Catatan',
        ];
    }

    public function map($attendance): array
    {
        $statusLabels = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Absen',
            'sick' => 'Sakit',
            'leave' => 'Cuti',
        ];

        return [
            $attendance->date->format('d/m/Y'),
            $attendance->teacher->user->name ?? '-',
            $attendance->teacher->nip ?? '-',
            $attendance->teacher->class_name ?? '-',
            $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-',
            $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-',
            $statusLabels[$attendance->status] ?? $attendance->status,
            $attendance->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '0284c7']]],
        ];
    }
}
