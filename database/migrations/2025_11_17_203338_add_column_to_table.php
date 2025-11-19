<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->text('supervisor_evaluation')->nullable()->after('graded_at');
            $table->integer('performance_rating')->nullable()->after('supervisor_evaluation');
            $table->timestamp('evaluated_at')->nullable()->after('performance_rating');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['supervisor_evaluation', 'performance_rating', 'evaluated_at']);
        });
    }
};
