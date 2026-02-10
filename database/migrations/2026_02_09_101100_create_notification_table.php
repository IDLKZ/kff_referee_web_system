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
        // Уведомления пользователей (запросы судьям, подтверждения бригад и т.д.)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Получатель');
            $table->string('type', 100)->comment('Тип: judge_request, brigade_confirmed, trip_created...');
            $table->morphs('notifiable'); // notifiable_type + notifiable_id (match, trip, etc.)
            $table->json('data')->nullable()->comment('Дополнительные данные уведомления');
            $table->timestamp('read_at')->nullable()->comment('Дата прочтения (null = не прочитано)');
            $table->timestamps();

            // Индексы
            $table->index(['user_id', 'read_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
