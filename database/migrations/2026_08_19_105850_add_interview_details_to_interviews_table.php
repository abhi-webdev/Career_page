<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {

            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();

            $table->date('interview_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->string('meeting_link');

            $table->text('notes')->nullable();

            $table->string('status')
                ->default('scheduled');

        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {

            $table->dropForeign([
                'application_id'
            ]);

            $table->dropColumn([
                'application_id',
                'interview_date',
                'start_time',
                'end_time',
                'meeting_link',
                'notes',
                'status',
            ]);

        });
    }
};