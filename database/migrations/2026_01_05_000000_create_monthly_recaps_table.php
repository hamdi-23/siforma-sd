<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_recaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->integer('total_days')->comment('Total hari kerja');
            $table->integer('present_days')->comment('Hari hadir');
            $table->integer('absent_days')->comment('Hari absen');
            $table->integer('late_days')->comment('Hari terlambat');
            $table->integer('sick_days')->comment('Hari sakit');
            $table->integer('leave_days')->comment('Hari cuti');
            $table->integer('total_reports_submitted')->comment('Jumlah laporan harian dikirim');
            $table->integer('total_reports_reviewed')->comment('Jumlah laporan harian di-review');
            $table->decimal('attendance_percentage', 5, 2)->nullable()->comment('Persentase kehadiran');
            $table->text('summary')->nullable()->comment('Ringkasan bulanan');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->unique(['teacher_id', 'year', 'month']);
            $table->index(['year', 'month']);
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_recaps');
    }
};
