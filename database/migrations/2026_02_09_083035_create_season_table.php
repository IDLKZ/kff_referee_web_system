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
        // Сезоны (например: «Сезон 2026»)
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru', 255);
            $table->string('title_kk', 255);
            $table->string('title_en', 255);
            $table->string('value', 280)->unique()->comment('Системный код сезона');
            $table->date('start_at')->nullable()->comment('Дата начала сезона');
            $table->date('end_at')->nullable()->comment('Дата окончания сезона');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
