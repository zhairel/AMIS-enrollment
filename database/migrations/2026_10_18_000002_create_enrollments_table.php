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
        if (Schema::hasTable('enrollments')) {
            return;
        }

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->string('academic_year', 20)->index();
            $table->enum('student_type', ['OLD', 'NEW', 'TRANSFEREE'])->default('NEW');
            $table->string('grade_level', 50);
            $table->enum('status', ['DRAFT', 'SUBMITTED', 'ENROLLED', 'REJECTED'])->default('DRAFT');
            $table->timestamps();

            $table->unique(['student_id', 'academic_year'], 'unique_student_academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
