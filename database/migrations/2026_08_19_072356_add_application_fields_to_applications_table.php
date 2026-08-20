<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('job_id')
                ->after('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('resume_id')
                ->nullable()
                ->after('job_id')
                ->constrained('resumes')
                ->nullOnDelete();

            $table->string('status')
                ->default('pending')
                ->after('resume_id');

            $table->text('cover_letter')
                ->nullable()
                ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->dropForeign(['user_id']);
            $table->dropForeign(['job_id']);
            $table->dropForeign(['resume_id']);

            $table->dropColumn([
                'user_id',
                'job_id',
                'resume_id',
                'status',
                'cover_letter',
            ]);

        });
    }
};