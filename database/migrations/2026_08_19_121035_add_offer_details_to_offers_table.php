<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {

            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();

            $table->decimal('salary', 12, 2);

            $table->date('joining_date');

            $table->date('offer_expiry_date')->nullable();

            $table->string('offer_letter_path')->nullable();

            $table->text('notes')->nullable();

            $table->string('status')
                ->default('draft');

        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {

            $table->dropForeign([
                'application_id'
            ]);

            $table->dropColumn([
                'application_id',
                'salary',
                'joining_date',
                'offer_expiry_date',
                'offer_letter_path',
                'notes',
                'status',
            ]);

        });
    }
};