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
        // Справочник типов транспорта (самолёт, поезд, автобус и т.д.)
        Schema::create('transport_types', function (Blueprint $table) {
            $table->id();
            $table->string('title_ru', 255);
            $table->string('title_kk', 255);
            $table->string('title_en', 255);
            $table->string('value', 280)->unique()->comment('Системный код типа транспорта');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_types');
    }
};
