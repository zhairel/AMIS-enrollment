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
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('student_number', 50)->unique();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('middle_name', 100)->nullable();
                $table->string('email')->nullable();
                $table->string('contact_number', 50)->nullable();
                $table->string('guardian_name', 255)->nullable();
                $table->string('guardian_contact', 50)->nullable();
                $table->string('guardian_relationship', 100)->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('student_number');
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name', 100)->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('students', 'contact_number')) {
                $table->string('contact_number', 50)->nullable()->after('email');
            }
            if (!Schema::hasColumn('students', 'guardian_name')) {
                $table->string('guardian_name', 255)->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('students', 'guardian_contact')) {
                $table->string('guardian_contact', 50)->nullable()->after('guardian_name');
            }
            if (!Schema::hasColumn('students', 'guardian_relationship')) {
                $table->string('guardian_relationship', 100)->nullable()->after('guardian_contact');
            }

            // Ensure legacy columns are nullable so master identities can be created without legacy dependencies
            if (Schema::hasColumn('students', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'enrollment_applicant_id')) {
                $table->unsignedBigInteger('enrollment_applicant_id')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'grade_level')) {
                $table->string('grade_level', 50)->nullable()->change();
            }
            if (Schema::hasColumn('students', 'school_year')) {
                $table->string('school_year', 20)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $columns = [
                    'first_name',
                    'last_name',
                    'middle_name',
                    'email',
                    'contact_number',
                    'guardian_name',
                    'guardian_contact',
                    'guardian_relationship',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('students', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
