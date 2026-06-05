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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->date('report_date');
            $table->string('class')->comment('Kelas yang diajar');
            $table->text('learning_objectives')->comment('Tujuan pembelajaran');
            $table->text('learning_materials')->comment('Materi pembelajaran');
            $table->text('teaching_methods')->comment('Metode pembelajaran');
            $table->text('student_response')->nullable()->comment('Respons siswa');
            $table->text('assignments_given')->nullable()->comment('Tugas yang diberikan');
            $table->integer('attendance_count')->nullable()->comment('Jumlah siswa hadir');
            $table->integer('total_students')->nullable()->comment('Total siswa');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->unique(['teacher_id', 'report_date']);
            $table->index('report_date');
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
