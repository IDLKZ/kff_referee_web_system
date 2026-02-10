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
        // Справочник удобств номеров (мини-бар, сейф, балкон и т.д.)
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->text('title_ru');
            $table->text('title_kk')->nullable();
            $table->text('title_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
