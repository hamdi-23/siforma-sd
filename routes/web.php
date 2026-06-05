<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyRecapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, request('remember'))) {
            request()->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Attendance routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
        Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('show');
        Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
    });

    // Daily Report routes
    Route::prefix('daily-report')->name('daily-report.')->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('index');
        Route::get('/create', [DailyReportController::class, 'create'])->name('create');
        Route::post('/', [DailyReportController::class, 'store'])->name('store');
        Route::get('/{dailyReport}', [DailyReportController::class, 'show'])->name('show');
        Route::get('/{dailyReport}/edit', [DailyReportController::class, 'edit'])->name('edit');
        Route::put('/{dailyReport}', [DailyReportController::class, 'update'])->name('update');
        Route::post('/{dailyReport}/review', [DailyReportController::class, 'review'])->name('review');
    });

    // Monthly Recap routes
    Route::prefix('monthly-recap')->name('monthly-recap.')->group(function () {
        Route::get('/', [MonthlyRecapController::class, 'index'])->name('index');
        Route::get('/{monthlyRecap}', [MonthlyRecapController::class, 'show'])->name('show');

        // Generate routes (admin/principal only)
        Route::middleware('admin.principal')->group(function () {
            Route::post('/generate/{teacher_id}/{year}/{month}', [MonthlyRecapController::class, 'generate'])->name('generate');
            Route::post('/generate-all/{year}/{month}', [MonthlyRecapController::class, 'generateAll'])->name('generate-all');
        });
    });
});
