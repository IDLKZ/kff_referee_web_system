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
        // Категории бизнес-процессов (связный список: назначение → командировка → отчётность)
        Schema::create('category_operations', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru', 255);
            $table->string('title_kk', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->string('value', 280)->unique()->comment('Системный код категории');
            $table->boolean("is_first")->default(false)->comment('Начальная категория в цепочке');
            $table->boolean("is_last")->default(false)->comment('Конечная категория в цепочке');
            $table->foreignId('previous_id')->nullable()->constrained('category_operations')->onDelete('set null');
            $table->foreignId('next_id')->nullable()->constrained('category_operations')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_operations');
    }
};
