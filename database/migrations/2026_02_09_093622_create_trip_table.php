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
        // Командировки судей на матч (одна командировка = один судья на один матч)
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade')->comment('Текущий шаг бизнес-процесса командировки');
            $table->foreignId('departure_city_id')->nullable()->constrained('cities')->onDelete('set null')->comment('Город отправления');
            $table->foreignId('arrival_city_id')->nullable()->constrained('cities')->onDelete('set null')->comment('Город прибытия');
            $table->string('name', 255)->nullable()->comment('Название командировки');
            $table->date('departure_date')->nullable()->comment('Дата выезда');
            $table->date('return_date')->nullable()->comment('Дата возвращения');
            $table->foreignId('transport_type_id')->constrained('transport_types')->onDelete('cascade')->comment('Основной тип транспорта');
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('logist_id')->nullable()->constrained('users')->onDelete('set null')->comment('Ответственный логист');
            $table->text('info')->nullable();
            $table->text('judge_comment')->nullable()->comment('Комментарий судьи по командировке');
            $table->unique(['match_id', 'judge_id'], 'trips_match_judge_unique');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
