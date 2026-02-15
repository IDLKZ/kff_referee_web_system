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
        // Требования к протоколам матча (какие документы должен загрузить каждый тип судьи)
        // Если match_id = null — шаблон по турниру; если задан — переопределение для конкретного матча
        Schema::create('match_protocol_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('match_id')->nullable()->constrained('matches')->onDelete('cascade')->comment('null = шаблон турнира');
            $table->foreignId('judge_type_id')->constrained('judge_types')->onDelete('cascade');
            $table->string('title_ru', 255);
            $table->string('title_kk', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->text('info_ru')->comment('Инструкция по заполнению');
            $table->text('info_kk')->nullable();
            $table->text('info_en')->nullable();
            $table->boolean('is_required')->default(true)->comment('Обязательный ли документ');
            $table->json('extensions')->nullable()->comment('Допустимые расширения файлов ["pdf","jpg"]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_protocol_requirements');
    }
};
