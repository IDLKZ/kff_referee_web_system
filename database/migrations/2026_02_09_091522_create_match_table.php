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
        // Матчи — центральная сущность системы
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('season_id')->constrained('seasons')->onDelete('cascade');
            $table->foreignId('stadium_id')->constrained('stadiums')->onDelete('cascade');
            $table->foreignId('current_operation_id')->constrained('operations')->onDelete('cascade')->comment('Текущий шаг бизнес-процесса');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade')->comment('Город проведения');
            $table->foreignId('owner_club_id')->constrained('clubs')->onDelete('cascade')->comment('Хозяева');
            $table->foreignId('guest_club_id')->constrained('clubs')->onDelete('cascade')->comment('Гости');
            $table->foreignId('winner_id')->nullable()->constrained('clubs')->onDelete('set null')->comment('Победитель (null=ничья или не завершён)');
            $table->integer('owner_point')->nullable()->comment('Счёт хозяев');
            $table->integer('guest_point')->nullable()->comment('Счёт гостей');
            $table->unsignedInteger('round')->nullable()->comment('Тур / игровая неделя');
            $table->dateTime('start_at')->comment('Дата и время начала матча');
            $table->dateTime('end_at')->nullable()->comment('Заполняется после завершения матча');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_finished')->default(false)->comment('Матч завершён');
            $table->boolean('is_canceled')->default(false)->comment('Матч отменён');
            $table->text('cancel_reason')->nullable()->comment('Причина отмены');
            $table->json('info')->nullable()->comment('Дополнительная информация (JSON)');
            $table->timestamps();
            $table->softDeletes();

            // Индексы для частых запросов
            $table->index('start_at');
            $table->index('is_finished');
            $table->index(['season_id', 'tournament_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
