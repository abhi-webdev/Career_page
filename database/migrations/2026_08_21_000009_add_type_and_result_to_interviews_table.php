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
        Schema::table('interviews', function (Blueprint $table) {
            $table->string('type')->default('hr')->after('application_id'); // 'hr', 'technical'
            $table->string('result')->default('pending')->after('status'); // 'pending', 'passed', 'failed'
            $table->foreignId('interviewer_id')->nullable()->after('result')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropForeign(['interviewer_id']);
            $table->dropColumn([
                'type',
                'result',
                'interviewer_id',
            ]);
        });
    }
};
