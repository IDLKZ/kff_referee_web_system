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
        // Справочник разрешений (гранулярные права доступа)
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string("title_ru", 255);
            $table->string("title_kk", 255)->nullable();
            $table->string("title_en", 255)->nullable();
            $table->string("value", 280)->unique()->index("idx_permission_value")->comment('Системный код разрешения');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
