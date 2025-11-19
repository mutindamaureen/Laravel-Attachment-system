<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('final_grade')->nullable()->after('report');
            $table->text('grading_comments')->nullable()->after('final_grade');
            $table->timestamp('graded_at')->nullable()->after('grading_comments');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['final_grade', 'grading_comments', 'graded_at']);
        });
    }
};
