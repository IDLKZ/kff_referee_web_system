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
        // Справочник типов клубов (профессиональный, любительский, молодёжный и т.д.)
        Schema::create('club_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null')->comment('Иконка типа');
            $table->string('title_ru', 255);
            $table->string('title_kk', 255);
            $table->string('title_en', 255);
            $table->string('value', 280)->unique()->comment('Системный код типа клуба');
            $table->integer('level')->comment('Уровень типа клуба');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_types');
    }
};
