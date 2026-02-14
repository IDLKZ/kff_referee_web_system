<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_judges', function (Blueprint $table) {
            $table->boolean('is_actual')->nullable()->default(null)->after('final_comment');
        });
    }

    public function down(): void
    {
        Schema::table('match_judges', function (Blueprint $table) {
            $table->dropColumn('is_actual');
        });
    }
};
