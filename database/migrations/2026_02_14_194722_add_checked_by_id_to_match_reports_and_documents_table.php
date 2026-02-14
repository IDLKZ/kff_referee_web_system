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
        Schema::table('match_reports', function (Blueprint $table) {
            $table->foreignId('checked_by_id')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->foreignId('checked_by_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->dropForeign(['checked_by_id']);
            $table->dropColumn('checked_by_id');
        });

        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->dropForeign(['checked_by_id']);
            $table->dropColumn('checked_by_id');
        });
    }
};
