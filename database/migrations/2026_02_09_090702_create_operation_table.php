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
        // Операции бизнес-процесса (связный список шагов внутри категории)
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_operations')->onDelete('cascade');
            $table->text('title_ru');
            $table->text('title_kk');
            $table->text('title_en');
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value', 280)->unique();
            $table->boolean('is_first')->default(false)->comment('Первый шаг в цепочке');
            $table->boolean('is_last')->default(false)->comment('Последний шаг в цепочке');
            $table->boolean('can_reject')->default(false)->comment('Можно ли отклонить на этом шаге');
            $table->boolean('is_active')->default(true);
            $table->integer('result')->default(0)->comment('Результат операции: 0=нейтральный');
            $table->foreignId('previous_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->foreignId('next_id')->nullable()->constrained('operations')->onDelete('set null');
            $table->foreignId('on_reject_id')->nullable()->constrained('operations')->onDelete('set null')->comment('Шаг при отклонении');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
