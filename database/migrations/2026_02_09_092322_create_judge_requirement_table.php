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
        // Требования к составу бригады судей на матч (сколько судей какого типа нужно)
        Schema::create('judge_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('judge_type_id')->constrained('judge_types')->onDelete('cascade');
            $table->unsignedInteger('qty')->comment('Необходимое количество судей данного типа');
            $table->boolean('is_required')->comment('Обязательна ли позиция');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judge_requirements');
    }
};
