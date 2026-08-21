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
        Schema::create('offer_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')
                ->constrained('offers')
                ->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->decimal('salary', 12, 2);
            $table->date('joining_date');
            $table->date('offer_expiry_date')->nullable();
            $table->string('offer_letter_path')->nullable();
            $table->string('signed_offer_letter_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->text('joining_date_note')->nullable();
            $table->date('requested_joining_date')->nullable();
            $table->string('joining_date_request_status')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_versions');
    }
};
