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
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('application_id');
            $table->string('signed_offer_letter_path')->nullable()->after('offer_letter_path');
            $table->timestamp('signed_at')->nullable()->after('signed_offer_letter_path');
            $table->text('decline_reason')->nullable()->after('status');
            $table->timestamp('declined_at')->nullable()->after('decline_reason');
            $table->text('joining_date_note')->nullable()->after('declined_at');
            $table->date('requested_joining_date')->nullable()->after('joining_date_note');
            $table->string('joining_date_request_status')->nullable()->after('requested_joining_date');
            $table->timestamp('joining_date_requested_at')->nullable()->after('joining_date_request_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'signed_offer_letter_path',
                'signed_at',
                'decline_reason',
                'declined_at',
                'joining_date_note',
                'requested_joining_date',
                'joining_date_request_status',
                'joining_date_requested_at',
            ]);
        });
    }
};
