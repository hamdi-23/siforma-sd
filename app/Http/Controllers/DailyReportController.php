<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DailyReportController extends Controller
{
    /**
     * Display a listing of daily reports.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            // Guru melihat laporan mereka sendiri
            $reports = $user->teacher->dailyReports()
                ->orderBy('report_date', 'desc')
                ->paginate(10);
        } else {
            // Admin/Principal melihat semua laporan
            $reports = DailyReport::with('teacher.user')
                ->orderBy('report_date', 'desc')
                ->paginate(15);
        }

        return view('daily-report.index', compact('reports'));
    }

    /**
     * Show the form for creating a new daily report.
     */
    public function create()
    {
        if (!Auth::user()->isTeacher()) {
            abort(403);
        }

        $classrooms = Classroom::orderBy('name')->get();
        return view('daily-report.create', compact('classrooms'));
    }

    /**
     * Store a newly created daily report.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403);
        }

        $validated = $request->validate([
            'report_date' => [
                'required',
                'date',
                Rule::unique('daily_reports')->where(function ($query) use ($user) {
                    return $query->where('teacher_id', $user->teacher->id);
                })
            ],
            'class' => 'required|string|max:50',
            'learning_materials' => 'required|string',
            'notes' => 'nullable|string',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // max 10MB
        ]);

        $validated['teacher_id'] = $user->teacher->id;
        $validated['status'] = $request->has('submit') ? 'submitted' : 'draft';

        if ($validated['status'] === 'submitted') {
            $validated['submitted_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('material_file')) {
            $file = $request->file('material_file');
            $fileName = $file->store('daily-reports', 'public');
            $validated['material_file'] = $fileName;
            $validated['material_file_original_name'] = $file->getClientOriginalName();
        }

        DailyReport::create($validated);

        $message = $validated['status'] === 'submitted'
            ? 'Laporan berhasil dikirim.'
            : 'Laporan berhasil disimpan sebagai draft.';

        return redirect()->route('daily-report.index')
            ->with('success', $message);
    }

    /**
     * Display the specified daily report.
     */
    public function show(DailyReport $dailyReport)
    {
        $user = Auth::user();

        if ($user->isTeacher() && $user->teacher->id !== $dailyReport->teacher_id) {
            abort(403);
        }

        return view('daily-report.show', compact('dailyReport'));
    }

    /**
     * Show the form for editing the specified daily report.
     */
    public function edit(DailyReport $dailyReport)
    {
        $user = Auth::user();

        if ($user->isTeacher() && $user->teacher->id !== $dailyReport->teacher_id) {
            abort(403);
        }

        // Draft hanya bisa diedit oleh guru sendiri sebelum dikirim
        if ($user->isTeacher() && $dailyReport->status !== 'draft') {
            abort(403);
        }

        $classrooms = Classroom::orderBy('name')->get();
        return view('daily-report.edit', compact('dailyReport', 'classrooms'));
    }

    /**
     * Update the specified daily report.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        $user = Auth::user();

        if ($user->isTeacher() && $user->teacher->id !== $dailyReport->teacher_id) {
            abort(403);
        }

        // Draft hanya bisa diedit sebelum dikirim
        if ($user->isTeacher() && $dailyReport->status !== 'draft') {
            abort(403);
        }

        $validated = $request->validate([
            'class' => 'required|string|max:50',
            'learning_materials' => 'required|string',
            'notes' => 'nullable|string',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $newStatus = $request->has('submit') ? 'submitted' : 'draft';
        $validated['status'] = $newStatus;

        if ($newStatus === 'submitted' && $dailyReport->status !== 'submitted') {
            $validated['submitted_at'] = now();
        }

        // Handle file upload
        if ($request->hasFile('material_file')) {
            // Delete old file jika ada
            if ($dailyReport->material_file && Storage::disk('public')->exists($dailyReport->material_file)) {
                Storage::disk('public')->delete($dailyReport->material_file);
            }

            $file = $request->file('material_file');
            $fileName = $file->store('daily-reports', 'public');
            $validated['material_file'] = $fileName;
            $validated['material_file_original_name'] = $file->getClientOriginalName();
        }

        $dailyReport->update($validated);

        $message = $newStatus === 'submitted'
            ? 'Laporan berhasil dikirim.'
            : 'Laporan berhasil diperbarui.';

        return redirect()->route('daily-report.show', $dailyReport)
            ->with('success', $message);
    }

    /**
     * Review/approve a daily report (admin/principal only).
     */
    public function review(Request $request, DailyReport $dailyReport)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isPrincipal()) {
            abort(403);
        }

        if ($dailyReport->status === 'draft') {
            return redirect()->route('daily-report.show', $dailyReport)
                ->with('error', 'Laporan yang masih draft tidak dapat di-review.');
        }

        $dailyReport->update(['status' => 'reviewed']);

        return redirect()->route('daily-report.show', $dailyReport)
            ->with('success', 'Laporan berhasil di-review.');
    }
}
