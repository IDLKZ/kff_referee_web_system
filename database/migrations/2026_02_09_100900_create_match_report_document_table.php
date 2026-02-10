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
        // Загруженные документы отчёта (протоколы матча, загруженные судьями)
        Schema::create('match_report_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade')->comment('Загруженный файл');
            $table->foreignId('match_judge_id')->constrained('match_judges')->onDelete('cascade');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained('match_protocol_requirements')->onDelete('cascade')->comment('Какое требование закрывает');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->text('comment')->nullable()->comment('Комментарий судьи');
            $table->text('final_comment')->nullable()->comment('Комментарий проверяющего');
            $table->boolean("is_accepted")->nullable()->comment('Документ принят');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_report_documents');
    }
};
