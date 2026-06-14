@extends('layouts.app')

@section('title', 'Detail Rekap Bulanan')

@section('extra_css')
<style>
    @media print {
        @page { size: A4; margin: 1.5cm; }
        body { background-color: white !important; color: black !important; font-size: 11pt !important; }
        .app-sidebar, .topbar, .footer, .btn, .navbar, .alert { display: none !important; }
        .app-main { margin-left: 0 !important; padding: 0 !important; }
        .d-print-none { display: none !important; }
        .print-resume { display: block !important; font-family: 'Times New Roman', Times, serif; font-size: 14pt; line-height: 1.6; color: black; }
        .print-resume h2 { text-align: center; font-size: 18pt; font-weight: bold; margin-bottom: 5px; }
        .print-resume h3 { font-size: 14pt; font-weight: bold; border-bottom: 2px solid black; margin-top: 25px; margin-bottom: 15px; padding-bottom: 5px; }
        .print-resume table { width: 100%; border-collapse: collapse; }
        .print-resume .meta-table td { padding: 3px 0; vertical-align: top; }
        .print-resume .meta-table td:first-child { width: 180px; font-weight: bold; }
        .print-resume .data-table { margin-top: 15px; width: 60%; }
        .print-resume .data-table td { padding: 5px 0; border-bottom: 1px dotted #ccc; }
        .print-resume .data-table td:first-child { font-weight: bold; width: 60%; }
        .print-resume .data-table td:last-child { text-align: right; }
        .signature-box { margin-top: 50px; float: right; width: 300px; text-align: center; }
        .signature-line { margin-top: 80px; border-top: 1px solid black; }
    }
</style>
@endsection

@section('content')
<!-- Print Resume View (Hidden in normal view) -->
<div class="d-none print-resume" style="font-family: 'Times New Roman', Times, serif; color: black; line-height: 1.5; font-size: 12pt;">
    
    <!-- Kop Surat (Letterhead) -->
    <div style="border-bottom: 3px solid black; padding-bottom: 2px; margin-bottom: 15px;">
        <div style="border-bottom: 1px solid black; padding-bottom: 10px; position: relative;">
            <div style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 100px; text-align: center;">
                <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width: 70px; height: 70px; object-fit: contain; margin: 0 auto;">
                <div style="display: none; width: 70px; height: 70px; border: 2px solid black; border-radius: 50%; align-items: center; justify-content: center; margin: 0 auto; font-weight: bold; font-size: 12pt;">LOGO</div>
            </div>
            <div style="text-align: center; padding: 0 100px;">
                <h2 style="margin: 0; font-size: 14pt; font-weight: bold;">DINAS PENDIDIKAN</h2>
                <h1 style="margin: 0; font-size: 18pt; font-weight: bold;">SDN KARANGNUNGGAL</h1>
                <p style="margin: 0; font-size: 10pt;">Karangnunggal, Tasikmalaya, Jawa Barat</p>
                <p style="margin: 0; font-size: 9pt;">Website: www.sdnkarangnunggal.sch.id | Email: info@sdnkarangnunggal.sch.id</p>
            </div>
        </div>
    </div>

    <!-- Teacher Info -->
    <table style="width: 100%; margin-bottom: 15px; font-size: 11pt;">
        <tr>
            <td style="width: 25%; padding: 3px 0;"><strong>Nama Lengkap</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 73%;">{{ $monthlyRecap->teacher->user->name }}</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;"><strong>NIP / NUPTK</strong></td>
            <td>:</td>
            <td>{{ $monthlyRecap->teacher->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;"><strong>Tugas / Mata Pelajaran</strong></td>
            <td>:</td>
            <td>{{ $monthlyRecap->teacher->subject ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;"><strong>Periode Laporan</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::createFromFormat('m', $monthlyRecap->month)->format('F') }} {{ $monthlyRecap->year }}</td>
        </tr>
    </table>

    <!-- Attendance Stats -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr>
                <th colspan="2" style="border: 1px solid black; padding: 6px; background-color: #f2f2f2 !important; text-align: left; font-size: 11pt; -webkit-print-color-adjust: exact; color-adjust: exact;">I. REKAPITULASI KEHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px; width: 70%;">Jumlah Hari Efektif Kerja</td>
                <td style="border: 1px solid black; padding: 6px 10px; width: 30%; text-align: center;">{{ $monthlyRecap->total_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Hadir (Tepat Waktu)</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->present_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Terlambat</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->late_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Sakit</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->sick_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Cuti / Izin</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->leave_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Tanpa Keterangan (Alpha)</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->absent_days }} Hari</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;"><strong>Persentase Kehadiran</strong></td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;"><strong>{{ $monthlyRecap->attendance_percentage }}%</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Journal Stats -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr>
                <th colspan="2" style="border: 1px solid black; padding: 6px; background-color: #f2f2f2 !important; text-align: left; font-size: 11pt; -webkit-print-color-adjust: exact; color-adjust: exact;">II. KINERJA JURNAL PEMBELAJARAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px; width: 70%;">Laporan Jurnal Harian Dikirim</td>
                <td style="border: 1px solid black; padding: 6px 10px; width: 30%; text-align: center;">{{ $monthlyRecap->total_reports_submitted }} Dokumen</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; padding: 6px 10px;">Laporan Telah Di-review / Dinilai</td>
                <td style="border: 1px solid black; padding: 6px 10px; text-align: center;">{{ $monthlyRecap->total_reports_reviewed }} Dokumen</td>
            </tr>
        </tbody>
    </table>

    @if($monthlyRecap->summary)
    <div style="margin-bottom: 15px;">
        <strong>III. CATATAN / EVALUASI KEPALA SEKOLAH:</strong>
        <div style="border: 1px solid black; padding: 10px; margin-top: 5px; min-height: 60px; text-align: justify;">
            {{ $monthlyRecap->summary }}
        </div>
    </div>
    @endif

    <!-- Signatures -->
    <div style="width: 100%; margin-top: 30px; page-break-inside: avoid; display: flex; justify-content: space-between;">
        <div style="width: 45%; text-align: center;">
            <p>Mengetahui,<br>Kepala SDN Karangnunggal</p>
            <br><br><br>
            <p style="margin-bottom: 0;"><strong><u>( ......................................... )</u></strong></p>
            <p style="margin-top: 2px;">NIP. .........................</p>
        </div>
        <div style="width: 45%; text-align: center;">
            <p>Siforma, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Guru Yang Bersangkutan</p>
            <br><br><br>
            <p style="margin-bottom: 0;"><strong><u>{{ $monthlyRecap->teacher->user->name }}</u></strong></p>
            <p style="margin-top: 2px;">NIP. {{ $monthlyRecap->teacher->nip ?? '.........................' }}</p>
        </div>
    </div>
</div>

<div class="page-header d-print-none">
    <h1><i class="fas fa-chart-bar"></i> Rekap Bulanan</h1>
    <p>Periode: {{ \Carbon\Carbon::createFromFormat('m', $monthlyRecap->month)->format('F') }} {{ $monthlyRecap->year }}</p>
</div>

<!-- Teacher Info Card -->
<div class="card mb-4 border-0 shadow-sm d-print-none">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap gap-4 align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                    <i class="fas fa-chalkboard-teacher fa-lg"></i>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold">{{ $monthlyRecap->teacher->user->name }}</h5>
                    <span class="text-muted"><i class="fas fa-id-badge me-1"></i> NIP: {{ $monthlyRecap->teacher->nip ?? '-' }}</span>
                </div>
            </div>
            
            <div class="vr d-none d-md-block mx-2"></div>
            
            <div>
                <span class="d-block text-muted small text-uppercase fw-bold mb-1">Mata Pelajaran</span>
                <span class="fw-bold fs-6"><i class="fas fa-book me-1 text-primary"></i> {{ $monthlyRecap->teacher->subject ?? '-' }}</span>
            </div>
            
            <div class="vr d-none d-md-block mx-2"></div>
            
            <div>
                <span class="d-block text-muted small text-uppercase fw-bold mb-1">Dibuat Pada</span>
                <span class="fw-bold fs-6"><i class="fas fa-clock me-1 text-primary"></i> {{ $monthlyRecap->generated_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats -->
<div class="row mb-4 d-print-none">
    <div class="col-md-2">
        <div class="stat-card">
            <i class="fas fa-calendar fa-2x" style="color: #3498db;"></i>
            <div class="stat-label">Total Hari</div>
            <div class="stat-value">{{ $monthlyRecap->total_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card success">
            <i class="fas fa-check fa-2x"></i>
            <div class="stat-label">Hadir</div>
            <div class="stat-value">{{ $monthlyRecap->present_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card danger">
            <i class="fas fa-times fa-2x"></i>
            <div class="stat-label">Absen</div>
            <div class="stat-value">{{ $monthlyRecap->absent_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card warning">
            <i class="fas fa-hourglass-end fa-2x"></i>
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $monthlyRecap->late_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card info">
            <i class="fas fa-hospital fa-2x" style="color: #16a085;"></i>
            <div class="stat-label">Sakit</div>
            <div class="stat-value">{{ $monthlyRecap->sick_days }}</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="stat-card info">
            <i class="fas fa-umbrella fa-2x" style="color: #9b59b6;"></i>
            <div class="stat-label">Cuti</div>
            <div class="stat-value">{{ $monthlyRecap->leave_days }}</div>
        </div>
    </div>
</div>

<!-- Attendance Summary -->
<div class="row mb-4 d-print-none">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Ringkasan Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Persentase Kehadiran</span>
                        <strong>{{ $monthlyRecap->attendance_percentage }}%</strong>
                    </div>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar {{ $monthlyRecap->attendance_percentage >= 80 ? 'bg-success' : ($monthlyRecap->attendance_percentage >= 60 ? 'bg-warning' : 'bg-danger') }}"
                             role="progressbar"
                             style="width: {{ $monthlyRecap->attendance_percentage }}%"
                             aria-valuenow="{{ $monthlyRecap->attendance_percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <table class="table table-sm">
                    <tr>
                        <td>Hadir :</td>
                        <td><strong>{{ $monthlyRecap->present_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Terlambat :</td>
                        <td><strong>{{ $monthlyRecap->late_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Absen :</td>
                        <td><strong>{{ $monthlyRecap->absent_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Sakit :</td>
                        <td><strong>{{ $monthlyRecap->sick_days }}</strong> hari</td>
                    </tr>
                    <tr>
                        <td>Cuti :</td>
                        <td><strong>{{ $monthlyRecap->leave_days }}</strong> hari</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Reports Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> Ringkasan Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-md-6">
                        <h6>Laporan Dikirim</h6>
                        <h3 class="text-primary">{{ $monthlyRecap->total_reports_submitted }}</h3>
                    </div>
                    <div class="col-md-6">
                        <h6>Laporan Di-review</h6>
                        <h3 class="text-success">{{ $monthlyRecap->total_reports_reviewed }}</h3>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong><br>
                    Total laporan pembelajaran harian yang dikirim: <strong>{{ $monthlyRecap->total_reports_submitted }}</strong><br>
                    Laporan yang sudah di-review: <strong>{{ $monthlyRecap->total_reports_reviewed }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row d-print-none">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i> Ringkasan Bulanan</h5>
            </div>
            <div class="card-body">
                @if($monthlyRecap->summary)
                    <p>{{ $monthlyRecap->summary }}</p>
                @else
                    <p class="text-muted">Tidak ada ringkasan</p>
                @endif
            </div>
        </div>
    </div>
</div>



<!-- Action Buttons -->
<div class="row mt-4 mb-4 d-print-none">
    <div class="col-md-12">
        <div class="d-grid gap-2 d-md-flex justify-content-end">
            <a href="{{ route('monthly-recap.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
</div>
@endsection
