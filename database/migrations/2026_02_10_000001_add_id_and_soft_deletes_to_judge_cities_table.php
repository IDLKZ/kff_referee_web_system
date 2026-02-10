<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns if they don't exist yet (handles partial previous run)
        Schema::table('judge_cities', function (Blueprint $table) {
            if (!Schema::hasColumn('judge_cities', 'id')) {
                $table->id()->first();
            }
            if (!Schema::hasColumn('judge_cities', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('judge_cities', 'created_at')) {
                $table->timestamps();
            }
        });

        // Drop unique index: need to drop foreign keys first in MySQL
        Schema::table('judge_cities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['city_id']);
        });

        Schema::table('judge_cities', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'city_id']);
        });

        Schema::table('judge_cities', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('judge_cities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['city_id']);
            $table->unique(['user_id', 'city_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->dropSoftDeletes();
            $table->dropTimestamps();
            $table->dropColumn('id');
        });
    }
};
