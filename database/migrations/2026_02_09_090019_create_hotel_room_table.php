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
        // Типы номеров в отелях
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null')->comment('Фото номера');
            $table->text('title_ru');
            $table->text('title_kk')->nullable();
            $table->text('title_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedInteger('bed_quantity')->comment('Количество кроватей');
            $table->decimal('room_size', 8, 2)->comment('Площадь номера (м²)');
            $table->boolean('air_conditioning')->default(false);
            $table->boolean('private_bathroom')->default(false);
            $table->boolean('tv')->default(false);
            $table->boolean('wifi')->default(false);
            $table->boolean('smoking_allowed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
