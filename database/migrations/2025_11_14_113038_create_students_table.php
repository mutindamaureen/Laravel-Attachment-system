<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('year_of_study')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('company_supervisor_id')->nullable()->constrained('company_supervisors')->onDelete('set null');
            $table->text('company_supervisor_comment')->nullable();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->onDelete('set null');
            $table->text('lecturer_comment')->nullable();
            $table->text('daily_activity')->nullable();
            $table->string('report')->nullable();
            $table->string('grade')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

