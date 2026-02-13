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
        Schema::table('match_logists', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropForeign(['logist_id']);
            $table->dropPrimary(['match_id', 'logist_id']);
        });

        Schema::table('match_logists', function (Blueprint $table) {
            $table->id()->first();
            $table->unique(['match_id', 'logist_id']);
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->foreign('logist_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_logists', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropForeign(['logist_id']);
            $table->dropColumn('id');
            $table->dropUnique(['match_id', 'logist_id']);
            $table->primary(['match_id', 'logist_id']);
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->foreign('logist_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
