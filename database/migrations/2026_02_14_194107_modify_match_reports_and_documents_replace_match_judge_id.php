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
            $table->dropForeign(['match_judge_id']);
            $table->dropColumn('match_judge_id');
            $table->foreignId('match_id')->after('id')->constrained('matches')->onDelete('cascade');
        });

        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->dropForeign(['match_judge_id']);
            $table->dropColumn('match_judge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_reports', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropColumn('match_id');
            $table->foreignId('match_judge_id')->after('id')->constrained('match_judges')->onDelete('cascade');
        });

        Schema::table('match_report_documents', function (Blueprint $table) {
            $table->foreignId('match_judge_id')->after('file_id')->constrained('match_judges')->onDelete('cascade');
        });
    }
};
