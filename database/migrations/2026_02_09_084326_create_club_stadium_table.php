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
        // Привязка стадионов к клубам (many-to-many, клуб может иметь несколько домашних стадионов)
        Schema::create('club_stadiums', function (Blueprint $table) {
            $table->foreignId('club_id')->constrained('clubs')->onDelete('cascade');
            $table->foreignId('stadium_id')->constrained('stadiums')->onDelete('cascade');
            $table->primary(['club_id', 'stadium_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_stadiums');
    }
};
