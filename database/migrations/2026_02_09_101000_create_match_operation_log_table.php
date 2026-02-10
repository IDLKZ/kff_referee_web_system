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
        // История переходов матча по шагам бизнес-процесса (аудит-лог)
        Schema::create('match_operation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('from_operation_id')->nullable()->constrained('operations')->onDelete('set null')->comment('Предыдущий шаг (null = создание матча)');
            $table->foreignId('to_operation_id')->constrained('operations')->onDelete('cascade')->comment('Новый шаг');
            $table->foreignId('performed_by_id')->constrained('users')->onDelete('cascade')->comment('Кто выполнил переход');
            $table->text('comment')->nullable()->comment('Комментарий к действию');
            $table->timestamps();
            // Индексы
            $table->index('match_id');
            $table->index('performed_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_operation_logs');
    }
};
