<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'resume_id')) {
                $table->foreignId('resume_id')
                    ->nullable()
                    ->after('job_id')
                    ->constrained('resumes')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'resume_id')) {
                $table->dropForeign(['resume_id']);
                $table->dropColumn('resume_id');
            }
        });
    }
};