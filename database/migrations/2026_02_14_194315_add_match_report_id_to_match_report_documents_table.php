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
        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->foreignId('match_report_id')->after('id')->constrained('match_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->dropForeign(['match_report_id']);
            $table->dropColumn('match_report_id');
        });
    }
};
