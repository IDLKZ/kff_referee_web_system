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
        // Отчёт судьи по матчу (один судья — один отчёт)
        Schema::create('match_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_judge_id')->constrained('match_judges')->onDelete('cascade');
            $table->boolean("is_finished")->default(false)->comment('Отчёт завершён и отправлен');
            $table->boolean("is_accepted")->nullable()->comment('Отчёт принят: null=на рассмотрении, true=принят, false=отклонён');
            $table->text('final_comment')->nullable()->comment('Комментарий проверяющего');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_reports');
    }
};
