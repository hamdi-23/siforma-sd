<?php

namespace App\Jobs;

use App\Models\DataExport;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessAttendanceExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes timeout

    protected $dataExport;
    protected $filters;

    /**
     * Create a new job instance.
     */
    public function __construct(DataExport $dataExport, array $filters)
    {
        $this->dataExport = $dataExport;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->dataExport->update(['status' => 'processing']);

            // Reconstruct query
            $query = Attendance::query();

            if (isset($this->filters['teacher_id'])) {
                $query->where('teacher_id', $this->filters['teacher_id']);
            }

            if (isset($this->filters['start_date']) && isset($this->filters['end_date'])) {
                $query->byDateRange($this->filters['start_date'], $this->filters['end_date']);
            }

            if (isset($this->filters['status'])) {
                $query->where('status', $this->filters['status']);
            }

            $dateStr = now()->format('Y-m-d_H-i-s');
            
            if ($this->dataExport->type === 'excel') {
                $fileName = "exports/Data_Presensi_{$dateStr}_{$this->dataExport->id}.xlsx";
                // store natively supports storing to a disk
                Excel::store(new AttendanceExport($query), $fileName, 'public');
                $this->dataExport->update([
                    'status' => 'completed',
                    'file_path' => $fileName
                ]);
            } else if ($this->dataExport->type === 'pdf') {
                $fileName = "exports/Data_Presensi_{$dateStr}_{$this->dataExport->id}.pdf";
                
                $attendances = $query->with(['teacher.user'])->orderBy('date', 'desc')->get();
                $pdf = Pdf::loadView('attendance.pdf', compact('attendances'))->setPaper('a4', 'landscape');
                
                // Save to public storage
                Storage::disk('public')->put($fileName, $pdf->output());
                
                $this->dataExport->update([
                    'status' => 'completed',
                    'file_path' => $fileName
                ]);
            }
        } catch (Throwable $e) {
            $this->dataExport->update(['status' => 'failed']);
            throw $e; // Re-throw to let the queue manager know it failed
        }
    }
}
