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
        // Отели для размещения судей во время командировок
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null')->comment('Фото отеля');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->text('title_ru');
            $table->text('title_kk')->nullable();
            $table->text('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->tinyInteger('star')->unsigned()->default(0)->comment('Количество звёзд отеля (0-5)');
            $table->string('email', 255)->nullable();
            $table->text('address_ru')->nullable();
            $table->text('address_kk')->nullable();
            $table->text('address_en')->nullable();
            $table->text('website')->nullable();
            $table->decimal('lat', 10, 7)->nullable()->comment('Широта');
            $table->decimal('lon', 10, 7)->nullable()->comment('Долгота');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_partner')->default(false)->comment('Партнёрский отель КФФ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
