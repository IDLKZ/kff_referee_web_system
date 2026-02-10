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
        // Назначение судей на матч (запрос → ответ судьи → финальное подтверждение)
        Schema::create('match_judges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('judge_types')->onDelete('cascade')->comment('Тип судьи на матче');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->text('request_comment')->nullable()->comment('Комментарий при отправке запроса');
            $table->tinyInteger('judge_response')->default(0)->comment('Ответ судьи: 0=ожидание, -1=отклонён, 1=принят');
            $table->text('judge_comment')->nullable()->comment('Комментарий судьи при ответе');
            $table->tinyInteger('final_status')->default(0)->comment('Финальный статус: 0=ожидание, -1=отклонён, 1=утверждён');
            $table->text('final_comment')->nullable()->comment('Комментарий при финальном решении');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->onDelete('set null')->comment('Кто создал назначение');
            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index('judge_id');
            $table->unique(['match_id', 'judge_id', 'type_id'], 'match_judges_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_judges');
    }
};
