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
        // Назначение логистов на матч (отвечают за командировки судей)
        Schema::create('match_logists', function (Blueprint $table) {
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('logist_id')->constrained('users')->onDelete('cascade');
            $table->primary(['match_id', 'logist_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_logists');
    }
};
