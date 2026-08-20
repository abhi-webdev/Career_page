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
    Schema::create('jobs', function (Blueprint $table) {
        $table->id();

        $table->string('title');

        $table->string('company');

        $table->text('description');

        $table->json('skills')->nullable();

        $table->string('location')->nullable();

        $table->string('job_type')->nullable();

        $table->string('experience')->nullable();

        $table->string('apply_url')->nullable();

        $table->dateTime('application_start')->nullable();

        $table->dateTime('application_deadline')->nullable();

        $table->dateTime('screening_date')->nullable();

        $table->dateTime('interview_start')->nullable();

        $table->dateTime('interview_end')->nullable();

        $table->dateTime('result_date')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
